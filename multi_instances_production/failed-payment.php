
/**
 * SNIPPET: Phoenix — Failed Payment Grace Period & Retry
 *
 * Flow saat payment gagal:
 *   1. WCS set subscription → on-hold (trigger default)
 *   2. Kita intercept: tandai grace period start, kirim email "Payment Failed"
 *   3. Action Scheduler jadwalkan retry D+3 dan D+7
 *   4. D+3: coba charge lagi via WCS renewal order
 *      - Sukses → subscription aktif kembali, kirim email "Payment Recovered"
 *      - Gagal  → kirim email reminder lagi
 *   5. D+7: retry terakhir
 *      - Sukses → aktif + email recovery
 *      - Gagal  → suspend (cancelled), kirim email "Subscription Suspended"
 *   6. Kapan saja dalam grace period: user update kartu → trigger retry langsung
 *
 * State tracking (post meta di subscription):
 *   _phoenix_grace_period_active  = 1
 *   _phoenix_grace_period_start   = timestamp
 *   _phoenix_retry_count          = 0/1/2
 *   _phoenix_last_retry_at        = timestamp
 *
 * Dependencies:
 *   - WooCommerce Subscriptions
 *   - Action Scheduler (bundled di WCS)
 *   - phoenix-email-notifications.php (phoenix_send_email, phoenix_email_wrap)
 *   - helper.php
 */

if (!defined('ABSPATH')) exit;

// Config — bisa disesuaikan
define('PHOENIX_GRACE_DAYS',    7);   // Total grace period (hari)
define('PHOENIX_RETRY_DAYS',    [3, 7]); // Hari ke berapa retry dicoba
define('PHOENIX_RETRY_MAX',     2);   // Maksimal retry attempts

// ============================================================================
// SECTION A — INTERCEPT PAYMENT FAILED (on-hold)
// ============================================================================

/**
 * Hook ke status transition on-hold
 * WCS set on-hold saat renewal payment gagal
 */
add_action('woocommerce_subscription_status_on-hold', 'phoenix_handle_payment_failed', 10, 1);
function phoenix_handle_payment_failed($subscription) {
    // Skip kalau bukan karena payment failure (e.g. manual on-hold oleh admin)
    // Cek apakah ada renewal order yang failed
    if (!phoenix_is_renewal_failure($subscription)) return;

    // Skip kalau grace period sudah aktif sebelumnya (avoid double trigger)
    if (get_post_meta($subscription->get_id(), '_phoenix_grace_period_active', true)) return;

    $sub_id  = $subscription->get_id();
    $user_id = $subscription->get_user_id();
    $now     = time();

    // Set grace period state
    update_post_meta($sub_id, '_phoenix_grace_period_active', 1);
    update_post_meta($sub_id, '_phoenix_grace_period_start',  $now);
    update_post_meta($sub_id, '_phoenix_retry_count',         0);

    // Log
    $subscription->add_order_note(phoenix_text('failed_payment.note_grace_started'));

    // Jadwalkan retry via Action Scheduler
    foreach (PHOENIX_RETRY_DAYS as $day) {
        as_schedule_single_action(
            $now + ($day * DAY_IN_SECONDS),
            'phoenix_retry_renewal',
            ['subscription_id' => $sub_id, 'attempt' => $day],
            'phoenix-billing'
        );
    }

    // Kirim email "Payment Failed — Action Required"
    
}

/**
 * Cek apakah on-hold dipicu oleh renewal failure
 */
function phoenix_is_renewal_failure($subscription) {
    // Ambil last renewal order
    $renewal_orders = $subscription->get_related_orders('ids', 'renewal');
    if (empty($renewal_orders)) return false;

    $last_order_id = max($renewal_orders);
    $last_order    = wc_get_order($last_order_id);
    if (!$last_order) return false;

    return in_array($last_order->get_status(), ['failed', 'pending']);
}

// ============================================================================
// SECTION B — RETRY HANDLER
// ============================================================================

add_action('phoenix_retry_renewal', 'phoenix_do_retry_renewal', 10, 2);
function phoenix_do_retry_renewal($subscription_id, $attempt) {
    $subscription = wcs_get_subscription($subscription_id);
    if (!$subscription) return;

    // Skip kalau sudah aktif kembali (user sudah update kartu dan berhasil)
    if ($subscription->get_status() === 'active') {
        // Grace period selesai dengan baik
        delete_post_meta($subscription_id, '_phoenix_grace_period_active');
        delete_post_meta($subscription_id, '_phoenix_grace_period_start');
        delete_post_meta($subscription_id, '_phoenix_retry_count');
        return;
    }

    // Skip kalau sudah cancelled (suspended sebelum retry ini)
    if (in_array($subscription->get_status(), ['cancelled', 'expired'])) return;

    // Skip kalau grace period sudah tidak aktif
    if (!get_post_meta($subscription_id, '_phoenix_grace_period_active', true)) return;

    $retry_count = (int) get_post_meta($subscription_id, '_phoenix_retry_count', true);
    update_post_meta($subscription_id, '_phoenix_retry_count', $retry_count + 1);
    update_post_meta($subscription_id, '_phoenix_last_retry_at', time());

    // Coba proses renewal
    $renewal_order = phoenix_create_renewal_order($subscription);
    if (!$renewal_order) {
        phoenix_handle_retry_failed($subscription, $attempt, $retry_count + 1);
        return;
    }

    // Trigger payment processing
    $result = phoenix_process_renewal_payment($subscription, $renewal_order);

    if ($result === true || $renewal_order->get_status() === 'completed') {
        // Sukses!
        phoenix_handle_retry_success($subscription, $renewal_order);
    } else {
        phoenix_handle_retry_failed($subscription, $attempt, $retry_count + 1);
    }
}

/**
 * Buat renewal order baru untuk retry
 */
function phoenix_create_renewal_order($subscription) {
    try {
        if (!function_exists('wcs_create_renewal_order')) return null;
        $renewal_order = wcs_create_renewal_order($subscription);
        if (is_wp_error($renewal_order)) return null;
        return $renewal_order;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Process payment untuk renewal order
 */
function phoenix_process_renewal_payment($subscription, $renewal_order) {
    try {
        $payment_method = $subscription->get_payment_method();
        $gateway        = WC()->payment_gateways()->payment_gateways()[$payment_method] ?? null;

        if (!$gateway) return false;

        // Trigger WCS scheduled payment
        do_action('woocommerce_scheduled_subscription_payment_' . $payment_method, $renewal_order->get_total(), $renewal_order);

        // Reload order untuk cek status terbaru
        $renewal_order = wc_get_order($renewal_order->get_id());
        return in_array($renewal_order->get_status(), ['completed', 'processing']);

    } catch (Exception $e) {
        return false;
    }
}

/**
 * Handler: retry berhasil
 */
function phoenix_handle_retry_success($subscription, $renewal_order) {
    $sub_id = $subscription->get_id();

    // Aktifkan kembali subscription
    $subscription->update_status('active', phoenix_text('failed_payment.note_retry_success'));

    // Bersihkan grace period meta
    delete_post_meta($sub_id, '_phoenix_grace_period_active');
    delete_post_meta($sub_id, '_phoenix_grace_period_start');
    delete_post_meta($sub_id, '_phoenix_retry_count');
    delete_post_meta($sub_id, '_phoenix_last_retry_at');

    // Cancel scheduled retry yang belum jalan
    as_unschedule_all_actions('phoenix_retry_renewal', ['subscription_id' => $sub_id], 'phoenix-billing');

    // Kirim email "Payment Recovered"
    
}

/**
 * Handler: retry gagal
 */
function phoenix_handle_retry_failed($subscription, $attempt, $retry_count) {
    $is_last_retry = ($retry_count >= PHOENIX_RETRY_MAX);

    if ($is_last_retry) {
        // Semua retry habis → suspend
        $subscription->update_status('cancelled', phoenix_text('failed_payment.note_all_failed'));

        // Bersihkan meta
        delete_post_meta($subscription->get_id(), '_phoenix_grace_period_active');
        delete_post_meta($subscription->get_id(), '_phoenix_grace_period_start');

        // Kirim email "Subscription Suspended"
        

    } else {
        // Masih ada retry lagi → kirim reminder
        $grace_start   = (int) get_post_meta($subscription->get_id(), '_phoenix_grace_period_start', true);
        $deadline      = $grace_start + (PHOENIX_GRACE_DAYS * DAY_IN_SECONDS);
        $days_left     = max(0, ceil(($deadline - time()) / DAY_IN_SECONDS));

        
    }
}

// ============================================================================
// SECTION C — TRIGGER RETRY SETELAH USER UPDATE KARTU
// ============================================================================

/**
 * Dipanggil dari phoenix-payment-method.php setelah user berhasil update kartu
 * dan subscription sebelumnya on-hold karena payment failed
 */
add_action('phoenix_retry_renewal_after_card_update', 'phoenix_do_retry_after_card_update', 10, 1);
function phoenix_do_retry_after_card_update($subscription_id) {
    $subscription = wcs_get_subscription($subscription_id);
    if (!$subscription) return;

    // Jadwalkan retry segera (1 menit dari sekarang, biar tidak blocking request)
    as_schedule_single_action(
        time() + 60,
        'phoenix_retry_renewal',
        ['subscription_id' => $subscription_id, 'attempt' => 'card_update'],
        'phoenix-billing'
    );

    $subscription->add_order_note(phoenix_text('failed_payment.note_card_updated'));
}

// ============================================================================
// SECTION D — GRACE PERIOD STATUS DI MY BILLING
// ============================================================================

/**
 * Hook: phoenix_billing_status_badge
 */
add_filter('phoenix_billing_status_badge', 'phoenix_grace_period_status_badge', 10, 2);
function phoenix_grace_period_status_badge($badge, $subscription) {
    if ($subscription->get_status() !== 'on-hold') return $badge;

    $grace_active = get_post_meta($subscription->get_id(), '_phoenix_grace_period_active', true);
    if (!$grace_active) return $badge;

    $grace_start = (int) get_post_meta($subscription->get_id(), '_phoenix_grace_period_start', true);
    $deadline    = $grace_start + (PHOENIX_GRACE_DAYS * DAY_IN_SECONDS);
    $days_left   = max(0, ceil(($deadline - time()) / DAY_IN_SECONDS));
    $retry_count = (int) get_post_meta($subscription->get_id(), '_phoenix_retry_count', true);

    return [
        'class' => 'badge-grace-period',
        'text'  => phoenix_text('failed_payment.badge_failed', $days_left),
    ];
}

/**
 * Tambah CSS untuk badge grace period
 */
add_action('wp_head', 'phoenix_grace_period_css');
function phoenix_grace_period_css() {
    if (!is_account_page()) return;
    echo '<style>
    .badge-grace-period {
        background: #FEF2F2 !important;
        color: #991B1B !important;
        border: 1px solid #FECACA !important;
        font-weight: 700;
    }
    .phoenix-pm-section button[data-phoenix-update-card]:hover {
        background: #1E4A7A !important;
        color: #fff !important;
    }
    </style>';
}

// ============================================================================
// SECTION F — ADMIN NOTICE: GRACE PERIOD SUBSCRIPTIONS
// ============================================================================

/**
 * Admin notice di WooCommerce subscription list
 * Tampilkan count subscription dalam grace period
 */
add_action('admin_notices', 'phoenix_admin_grace_period_notice');
function phoenix_admin_grace_period_notice() {
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'edit-shop_subscription') return;

    global $wpdb;
    $count = $wpdb->get_var("
        SELECT COUNT(DISTINCT post_id)
        FROM {$wpdb->postmeta}
        WHERE meta_key = '_phoenix_grace_period_active'
        AND meta_value = '1'
    ");

    if (!$count) return;

    echo '<div class="notice notice-warning" style="border-left-color:#F59E0B;">
        <p>' . phoenix_text('failed_payment.admin_notice', (int)$count) . '
        <a href="' . admin_url('edit.php?post_type=shop_subscription&_phoenix_grace=1') . '">View them →</a></p>
    </div>';
}