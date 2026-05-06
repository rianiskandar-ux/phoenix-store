
/**
 * SNIPPET: Phoenix Email Notifications — v4
 * Menulis user meta saat upgrade plan / pembelian add-on.
 * AutomateWoo pakai built-in trigger "Subscription Status Changed" + Rules.
 *
 * USER META yang ditulis (bisa dibaca di AutomateWoo via {{ customer.meta }}):
 *   _phoenix_last_upgrade_from    — nama plan sebelumnya
 *   _phoenix_last_upgrade_to      — nama plan baru
 *   _phoenix_last_upgrade_period  — monthly / yearly
 *   _phoenix_last_tenant_name     — nama instance
 *   _phoenix_last_tenant_url      — URL instance (tanpa .stg.)
 *   _phoenix_last_addon_name      — nama add-on yang dibeli
 *   _phoenix_last_addon_period    — billing period add-on
 *   _phoenix_last_tenant_uuid     — UUID instance
 *   _phoenix_event_type           — "plan_upgraded", "addon_purchased", atau "new_purchase"
 *
 * SETUP AUTOMATEWOO:
 *   Workflow 1 (Upgrade):
 *     Trigger : Subscription Status Changed
 *     Rules   : Subscription Status = active
 *               + Customer Custom Field _phoenix_event_type = plan_upgraded
 *     Email   : pakai {{ customer.meta }} dengan key di atas
 *
 *   Workflow 2 (Add-on):
 *     Trigger : Subscription Status Changed
 *     Rules   : Subscription Status = active
 *               + Subscription Item Categories includes Add-on
 *     Email   : pakai {{ customer.meta }} dengan key di atas
 *
 * Priority hook = 5 (lebih awal dari AutomateWoo priority 10)
 * agar meta sudah tertulis sebelum AutomateWoo evaluasi rules.
 */

// =============================================================================
// HELPER: plan info from subscription
// =============================================================================
function phoenix_email_get_plan_info( WC_Subscription $sub ): array {
    $hierarchy = function_exists('phoenix_get_plan_hierarchy')
        ? phoenix_get_plan_hierarchy()
        : [30688=>1, 11=>1, 58=>2, 61=>2, 62=>2, 76=>3, 78=>3, 79=>3];

    $level = 0; $name = ''; $is_addon = false;

    foreach ( $sub->get_items() as $item ) {
        $pid = $item->get_product_id();
        if ( has_term('add-on', 'product_cat', $pid) ) {
            $is_addon = true; $name = $item->get_name(); break;
        }
        if ( isset($hierarchy[$pid]) && $hierarchy[$pid] > $level ) {
            $level = $hierarchy[$pid]; $name = $item->get_name();
        }
    }

    $period    = $sub->get_billing_period();
    $interval  = (int) $sub->get_billing_interval();
    $is_yearly = ( $period === 'year' ) || ( $period === 'month' && $interval >= 12 );

    return [
        'level'     => $level,
        'name'      => $name,
        'is_addon'  => $is_addon,
        'is_yearly' => $is_yearly,
        'period'    => $is_yearly ? 'yearly' : 'monthly',
    ];
}

// =============================================================================
// HELPER: resolve tenant row by subscription_wc_id
// =============================================================================
function phoenix_email_get_tenant_by_sub( int $sub_id, int $user_id ): ?object {
    global $wpdb;
    $table = $wpdb->prefix . 'wbssaas_tenants';
    if ( $wpdb->get_var("SHOW TABLES LIKE '$table'") !== $table ) return null;
    return $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM $table WHERE subscription_wc_id = %d AND customer_id = %d LIMIT 1",
        $sub_id, $user_id
    ));
}

// =============================================================================
// HELPER: resolve tenant UUID from add-on subscription's parent order (3-strategy)
// =============================================================================
function phoenix_email_resolve_tenant_uuid_from_addon( WC_Subscription $addon_sub ): string {
    global $wpdb;
    $order_id = $addon_sub->get_parent_id();
    if ( ! $order_id ) return '';

    if ( function_exists('GFAPI') ) {
        $entries = GFAPI::get_entries(64, [
            'field_filters' => [['key' => 'woocommerce_order_number', 'value' => $order_id]]
        ]);
        if ( ! empty($entries) && ! empty($entries[0]['1']) ) return (string) $entries[0]['1'];
    }

    $order = wc_get_order($order_id);
    if ( $order ) {
        foreach ( $order->get_items() as $item ) {
            foreach ( $item->get_meta_data() as $meta ) {
                if ( is_string($meta->value) && preg_match('/^[0-9a-f\-]{32,}$/i', $meta->value) ) {
                    return $meta->value;
                }
            }
        }
    }

    $gf_val = $wpdb->get_var( $wpdb->prepare(
        "SELECT em.meta_value FROM {$wpdb->prefix}gf_entry_meta em
         INNER JOIN {$wpdb->prefix}gf_entry e ON e.id = em.entry_id
         WHERE (
             e.source_url LIKE %s
             OR e.id IN (
                 SELECT entry_id FROM {$wpdb->prefix}gf_entry_meta
                 WHERE meta_key = 'woocommerce_order_number' AND meta_value = %s
             )
         ) AND em.meta_key = '1' AND e.form_id = 64 LIMIT 1",
        '%order_id=' . $order_id . '%', (string) $order_id
    ));
    return $gf_val ? (string) $gf_val : '';
}

// =============================================================================
// HELPER: resolve tenant row by UUID
// =============================================================================
function phoenix_email_get_tenant_by_uuid( string $uuid, int $user_id ): ?object {
    global $wpdb;
    $table = $wpdb->prefix . 'wbssaas_tenants';
    if ( $wpdb->get_var("SHOW TABLES LIKE '$table'") !== $table ) return null;
    return $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM $table WHERE tenant_uuid = %s AND customer_id = %d LIMIT 1",
        $uuid, $user_id
    ));
}

function phoenix_email_clean_url( string $url ): string {
    return preg_replace('/\.stg\./i', '.', rtrim($url, '/'));
}

// =============================================================================
// HELPER: fallback — ambil tenant terbaru milik user (by created date)
// =============================================================================
function phoenix_email_get_tenant_latest_by_user( int $user_id ): ?object {
    global $wpdb;
    $table = $wpdb->prefix . 'wbssaas_tenants';
    if ( $wpdb->get_var("SHOW TABLES LIKE '$table'") !== $table ) return null;
    return $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM $table WHERE customer_id = %d ORDER BY id DESC LIMIT 1",
        $user_id
    ));
}

// =============================================================================
// SCENARIO 1: Plan Upgrade — tulis user meta
// Priority 5 = lebih awal dari AutomateWoo (default priority 10)
// =============================================================================
add_action( 'woocommerce_subscription_status_active', 'phoenix_detect_plan_upgrade', 5 );
function phoenix_detect_plan_upgrade( $subscription ) {
    if ( ! is_a($subscription, 'WC_Subscription') ) $subscription = wcs_get_subscription($subscription);
    if ( ! $subscription ) return;

    $info = phoenix_email_get_plan_info($subscription);
    if ( $info['is_addon'] || $info['level'] < 1 ) return;

    $user_id     = $subscription->get_user_id();
    $prev_name   = '';
    $prev_sub_id = 0;

    // STRATEGY 1: WCS switch flow — _subscription_switch on parent order
    // Set by WCS when user upgrades via switch URL (upgrade-subscriptions.php auto_redirect_next_tier)
    $parent_order_id = $subscription->get_parent_id();
    if ( $parent_order_id ) {
        $switch_data = get_post_meta( $parent_order_id, '_subscription_switch', true );
        if ( is_array( $switch_data ) ) {
            foreach ( $switch_data as $old_sub_id => $switch_info ) {
                $old_sub = wcs_get_subscription( (int) $old_sub_id );
                if ( ! $old_sub ) continue;
                $old_info = phoenix_email_get_plan_info( $old_sub );
                if ( $old_info['is_addon'] || $old_info['level'] < 1 ) continue;
                $prev_sub_id = (int) $old_sub_id;
                $prev_name   = $old_info['name'];
                break;
            }
        }
    }

    // STRATEGY 2: Custom add-to-cart upgrade flow (upgrade-plan.php)
    // phoenix_submit_upgrade_gf stores old sub_id via update_option before checkout
    if ( $prev_sub_id === 0 ) {
        $stored_sub_id = (int) get_option( '_phoenix_upgrade_from_sub_' . $user_id, 0 );
        if ( $stored_sub_id && $stored_sub_id !== $subscription->get_id() ) {
            $old_sub = wcs_get_subscription( $stored_sub_id );
            if ( $old_sub ) {
                $old_info = phoenix_email_get_plan_info( $old_sub );
                if ( ! $old_info['is_addon'] && $old_info['level'] >= 1 ) {
                    $prev_sub_id = $stored_sub_id;
                    $prev_name   = $old_info['name'];
                }
            }
        }
    }

    // SCENARIO 3: First-time purchase — schedule meta writing 90 detik kemudian
    // agar tenant sudah tersimpan ke DB oleh SaaS plugin sebelum kita lookup
    if ( $prev_sub_id === 0 ) {
        wp_schedule_single_event(
            time() + 90,
            'phoenix_write_new_purchase_meta',
            [ $subscription->get_id(), $user_id, $info['name'], $info['period'] ]
        );
        return;
    }

    // Clear option setelah dipakai (upgrade-plan.php tidak hapus ini sendiri)
    delete_option( '_phoenix_upgrade_from_sub_' . $user_id );

    $tenant = phoenix_email_get_tenant_by_sub($prev_sub_id, $user_id)
           ?: phoenix_email_get_tenant_by_sub($subscription->get_id(), $user_id);

    $tenant_name = $tenant ? $tenant->tenant_name : '';
    $tenant_url  = $tenant ? phoenix_email_clean_url($tenant->tenant_url ?? '') : '';

    // Tulis meta — AutomateWoo baca ini lewat rule "Customer Custom Field"
    update_user_meta($user_id, '_phoenix_event_type',          'plan_upgraded');
    update_user_meta($user_id, '_phoenix_last_upgrade_from',   $prev_name);
    update_user_meta($user_id, '_phoenix_last_upgrade_to',     $info['name']);
    update_user_meta($user_id, '_phoenix_last_upgrade_period', $info['period']);
    update_user_meta($user_id, '_phoenix_last_tenant_name',    $tenant_name);
    update_user_meta($user_id, '_phoenix_last_tenant_url',     $tenant_url);
}

// =============================================================================
// SCENARIO 2: Add-on Purchase — tulis user meta
// Priority 5 = lebih awal dari AutomateWoo
// =============================================================================
add_action( 'woocommerce_subscription_status_active', 'phoenix_detect_addon_purchase', 5 );
function phoenix_detect_addon_purchase( $subscription ) {
    if ( ! is_a($subscription, 'WC_Subscription') ) $subscription = wcs_get_subscription($subscription);
    if ( ! $subscription ) return;

    $info = phoenix_email_get_plan_info($subscription);
    if ( ! $info['is_addon'] ) return;

    $user_id     = $subscription->get_user_id();
    $addon_names = [];
    foreach ( $subscription->get_items() as $item ) {
        if ( has_term('add-on', 'product_cat', $item->get_product_id()) ) {
            $addon_names[] = $item->get_name();
        }
    }

    $tenant_uuid = phoenix_email_resolve_tenant_uuid_from_addon($subscription);
    $tenant      = $tenant_uuid ? phoenix_email_get_tenant_by_uuid($tenant_uuid, $user_id) : null;
    $tenant_name = $tenant ? $tenant->tenant_name : '';
    $tenant_url  = $tenant ? phoenix_email_clean_url($tenant->tenant_url ?? '') : '';

    update_user_meta($user_id, '_phoenix_event_type',         'addon_purchased');
    update_user_meta($user_id, '_phoenix_last_addon_name',    implode(', ', $addon_names));
    update_user_meta($user_id, '_phoenix_last_addon_period',  $info['period']);
    update_user_meta($user_id, '_phoenix_last_tenant_name',   $tenant_name);
    update_user_meta($user_id, '_phoenix_last_tenant_url',    $tenant_url);
}

// =============================================================================
// SCENARIO 3 HANDLER: jalankan 90 detik setelah new purchase
// Tenant sudah pasti ada di DB saat ini
// =============================================================================
add_action( 'phoenix_write_new_purchase_meta', 'phoenix_handle_new_purchase_meta', 10, 4 );
function phoenix_handle_new_purchase_meta( int $sub_id, int $user_id, string $plan_name, string $period ) {
    $tenant = phoenix_email_get_tenant_by_sub($sub_id, $user_id)
           ?: phoenix_email_get_tenant_latest_by_user($user_id);

    // Jika masih tidak ditemukan, coba lagi 5 menit kemudian (max 1x retry)
    if ( ! $tenant ) {
        $retry_key = '_phoenix_new_purchase_retry_' . $sub_id;
        if ( ! get_transient($retry_key) ) {
            set_transient($retry_key, 1, 600);
            wp_schedule_single_event( time() + 300, 'phoenix_write_new_purchase_meta', [ $sub_id, $user_id, $plan_name, $period ] );
        }
        return;
    }

    $tenant_url  = phoenix_email_clean_url($tenant->tenant_url ?? '');
    $tenant_uuid = $tenant->tenant_uuid ?? '';
    $tenant_name = $tenant->tenant_name ?? '';

    update_user_meta($user_id, '_phoenix_event_type',          'new_purchase');
    update_user_meta($user_id, '_phoenix_last_plan_name',      $plan_name);
    update_user_meta($user_id, '_phoenix_last_upgrade_period', $period);
    update_user_meta($user_id, '_phoenix_last_tenant_name',    $tenant_name);
    update_user_meta($user_id, '_phoenix_last_tenant_url',     $tenant_url);
    update_user_meta($user_id, '_phoenix_last_tenant_uuid',    $tenant_uuid);
}

// =============================================================================
// SCENARIO 4: Commitment Window Notification — tulis user meta saat renewal
// Dipakai AutomateWoo untuk trigger email:
//   - bulan 11 → _phoenix_commitment_window = 'open'  (cancel window buka)
//   - bulan 12 → _phoenix_commitment_window = 'complete' (commitment selesai, auto-renew lanjut)
//   - bulan lain → meta dihapus agar tidak ada stale trigger
//
// USER META:
//   _phoenix_commitment_window — 'open' | 'complete' | (deleted)
//
// SETUP AUTOMATEWOO:
//   Workflow A (Cancel Window Open):
//     Trigger : Subscription Renewal Payment Complete
//     Rules   : Customer Custom Field _phoenix_commitment_window = open
//     Email   : notif bahwa cancel window sudah tersedia di dashboard
//
//   Workflow B (Commitment Complete):
//     Trigger : Subscription Renewal Payment Complete
//     Rules   : Customer Custom Field _phoenix_commitment_window = complete
//     Email   : notif bahwa commitment 12 bulan selesai, auto-renew aktif
//
// Priority 5 = lebih awal dari AutomateWoo (default priority 10)
// Skip: addon subscriptions & free plan (level < 2)
// =============================================================================
add_action( 'woocommerce_subscription_renewal_payment_complete', 'phoenix_track_commitment_window', 5, 2 );
function phoenix_track_commitment_window( $subscription, $last_order ) {
    if ( ! is_a($subscription, 'WC_Subscription') ) return;

    $info = phoenix_email_get_plan_info($subscription);
    if ( $info['is_addon'] || $info['level'] < 2 ) return;

    if ( ! function_exists('phoenix_get_commitment_progress') ) return;

    $user_id  = $subscription->get_user_id();
    $progress = phoenix_get_commitment_progress($subscription);
    $months   = (int) ($progress['months'] ?? 0);

    if ( $months === 11 ) {
        update_user_meta($user_id, '_phoenix_commitment_window', 'open');
    } elseif ( $months >= 12 ) {
        update_user_meta($user_id, '_phoenix_commitment_window', 'complete');
    } else {
        delete_user_meta($user_id, '_phoenix_commitment_window');
    }
}
