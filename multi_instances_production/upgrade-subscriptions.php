// 0. BUGFIX: Sembunyikan tombol Upgrade/Switch dari semua subscription addon
//    WCS secara default nambah tombol switch ke semua variable subscription product,
//    termasuk addon. Filter ini hapus action 'switch' dari addon subscription.
add_filter('wcs_view_subscription_actions', 'hide_upgrade_button_from_addon_subscriptions', 5, 2);
function hide_upgrade_button_from_addon_subscriptions($actions, $subscription) {
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) {
            // Hapus semua action yang berhubungan dengan switch/upgrade
            unset($actions['switch']);
            unset($actions['upgrade']);
            // WCS kadang pakai key berbeda tergantung versi
            foreach ($actions as $key => $action) {
                if (isset($action['url']) && strpos($action['url'], 'switch-subscription') !== false) {
                    unset($actions[$key]);
                }
            }
            return $actions;
        }
    }
    return $actions;
}
 
// 1. Force allow switching antar plan
add_filter('wcs_can_user_switch_subscription', 'force_allow_plan_switching', 10, 3);
function force_allow_plan_switching($can_switch, $subscription, $product) {
    $switchable_ids = [30688, 58, 76];
    foreach ($subscription->get_items() as $item) {
        // BUGFIX: Skip addon subscriptions — jangan izinkan switch di addon
        if (has_term('add-on', 'product_cat', $item->get_product_id())) return false;
        if (in_array($item->get_product_id(), $switchable_ids)) return true;
    }
    return $can_switch;
}
 
// 2. Auto redirect ke tier berikutnya — match billing period
add_filter('wcs_switch_url', 'auto_redirect_next_tier', 10, 4);
function auto_redirect_next_tier($switch_url, $item, $subscription, $product) {
    // BUGFIX: Jangan proses switch untuk addon
    if (has_term('add-on', 'product_cat', $item->get_product_id())) return false;
 
    $tier_map = [30688 => 58, 58 => 76];
    $current_product_id = $item->get_product_id();
    if (!isset($tier_map[$current_product_id])) return false;
 
    $next_product_id = $tier_map[$current_product_id];
    $next_product    = wc_get_product($next_product_id);
    if (!$next_product) return $switch_url;
 
    $variations = $next_product->get_available_variations();
    if (empty($variations)) return $switch_url;
 
    $current_period   = $subscription->get_billing_period();
    $current_interval = $subscription->get_billing_interval();
    $current_yearly   = ($current_period === 'year') || ($current_period === 'month' && (int)$current_interval >= 12);
 
    $matched_variation_id  = null;
    $fallback_variation_id = null;
 
    foreach ($variations as $variation) {
        $var_obj      = wc_get_product($variation['variation_id']);
        if (!$var_obj) continue;
        $var_period   = $var_obj->get_billing_period();
        $var_interval = $var_obj->get_billing_interval();
        $var_yearly   = ($var_period === 'year') || ($var_period === 'month' && (int)$var_interval >= 12);
 
        if ($current_yearly && $var_yearly) { $matched_variation_id = $variation['variation_id']; break; }
        if (!$current_yearly && !$var_yearly) { $matched_variation_id = $variation['variation_id']; break; }
        if (!$fallback_variation_id) $fallback_variation_id = $variation['variation_id'];
    }
 
    $use_variation_id = $matched_variation_id ?: $fallback_variation_id;
    if (!$use_variation_id) return $switch_url;
 
    return get_permalink(get_page_by_path('upgrade-plan')) . '?sub_id=' . $subscription->get_id();
}
 
/* 3. Force payment method muncul saat checkout CHF0
add_filter('woocommerce_cart_needs_payment', 'force_payment_for_free_subscription', 10, 2);
function force_payment_for_free_subscription($needs_payment, $cart) {
    if (WC()->cart->get_total('numeric') == 0) {
        foreach (WC()->cart->get_cart() as $cart_item) {
            if (!isset($cart_item['data'])) continue;
            $product = $cart_item['data'];
            if ($product->is_type(['subscription', 'variable-subscription', 'subscription_variation'])) return true;
            $period = get_post_meta($product->get_id(), '_subscription_period', true);
            if (!$period && isset($cart_item['variation_id'])) {
                $period = get_post_meta($cart_item['variation_id'], '_subscription_period', true);
            }
            if ($period) return true;
        }
    }
    return $needs_payment;
}*/
 
// 4. Force save CC otomatis
add_action('woocommerce_checkout_process', 'force_save_card_on_subscription');
function force_save_card_on_subscription() {
    if (function_exists('wcs_cart_contains_subscription') && wcs_cart_contains_subscription()) {
        $_POST['wc-stripe-new-payment-method'] = 'true';
    }
}
 
add_filter('wc_stripe_payment_intent_args', 'use_setup_intent_for_zero_amount', 10, 2);
function use_setup_intent_for_zero_amount($args, $order) {
    if ($order->get_total() == 0) $args['setup_future_usage'] = 'off_session';
    return $args;
}
 
// ================================================================
// HELPER: phoenix_get_cancel_window
//
// MONTHLY commitment (12 bulan):
//   Patokan = hari tersisa menuju commitment_end (start + 12 bulan)
//   'locked'  → > 31 hari sebelum commitment_end (masih jauh)
//   'window'  → ≤ 31 hari sebelum commitment_end (bulan terakhir komitmen)
//   'free'    → sudah melewati commitment_end → bebas cancel
//
// YEARLY:
//   Selalu 'free' — tidak ada commitment lock
//
// Contoh dinamis:
//   Start: 5 Mar 2025 → commitment_end: 5 Mar 2026
//   Today: 10 Feb 2026 → 23 hari lagi → 'window' ✅
//   Today: 10 Jan 2026 → 54 hari lagi → 'locked'
//   Today: 10 Mar 2026 → sudah lewat  → 'free'
// ================================================================
function phoenix_get_cancel_window($subscription) {
    if (!is_a($subscription, 'WC_Subscription')) return 'locked';
 
    $billing_period = $subscription->get_billing_period();
    $interval       = (int) $subscription->get_billing_interval();
    $is_yearly      = ($billing_period === 'year') || ($billing_period === 'month' && $interval >= 12);
 
    // Yearly = selalu free (cancel kapan saja)
    if ($is_yearly) return 'free';
 
    // Monthly commitment — hitung dari start date dinamis
    $start = $subscription->get_time('start');
    if (!$start) return 'locked';
 
    $today          = current_time('timestamp');
    $commitment_end = strtotime('+12 months', $start); // dinamis dari start date
 
    // Sudah lewat commitment_end → free
    if ($today >= $commitment_end) return 'free';
 
    // Hitung hari tersisa menuju commitment_end
    $days_left = (int) ceil(($commitment_end - $today) / DAY_IN_SECONDS);
 
    // Window terbuka di bulan terakhir (≤ 31 hari sebelum commitment_end)
    if ($days_left <= 31) return 'window';
 
    return 'locked';
}
 
// ================================================================
// 5A. Disable cancel — block kalau 'locked', buka kalau 'window' atau 'free'
// ================================================================
add_filter('woocommerce_subscription_can_cancel', 'custom_disable_cancel_before_commitment', 10, 2);
function custom_disable_cancel_before_commitment($can_cancel, $subscription) {
    $billing_period = $subscription->get_billing_period();
    $interval       = (int) $subscription->get_billing_interval();
    $is_yearly      = ($billing_period === 'year') || ($billing_period === 'month' && $interval >= 12);
 
    // Yearly → handled by phoenix_yearly_allow_cancel
    if ($is_yearly) return $can_cancel;
    // Only monthly non-yearly
    if ($billing_period !== 'month') return $can_cancel;
 
    // Skip addon
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) return false;
        $n = strtolower($item->get_name());
        if (strpos($n, 'free') !== false || strpos($n, 'starter') !== false) return $can_cancel;
        if (in_array($item->get_product_id(), [11, 30688])) return $can_cancel;
    }
 
    $window = phoenix_get_cancel_window($subscription);
    return $window !== 'locked'; // locked = false, window/free = true
}
 
// ================================================================
// 5B. Hide cancel button kalau 'locked'
// ================================================================
add_filter('wcs_view_subscription_actions', 'custom_hide_cancel_button_during_commitment', 10, 2);
function custom_hide_cancel_button_during_commitment($actions, $subscription) {
    $billing_period = $subscription->get_billing_period();
    $interval       = (int) $subscription->get_billing_interval();
    $is_yearly      = ($billing_period === 'year') || ($billing_period === 'month' && $interval >= 12);
 
    if ($is_yearly) return $actions; // yearly handled separately
    if ($billing_period !== 'month') return $actions;
 
    // Skip addon & free
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) return $actions;
        $n = strtolower($item->get_name());
        if (strpos($n, 'free') !== false || strpos($n, 'starter') !== false) return $actions;
        if (in_array($item->get_product_id(), [11, 30688])) return $actions;
    }
 
    $window = phoenix_get_cancel_window($subscription);
    if ($window === 'locked') unset($actions['cancel']);
 
    return $actions;
}
 
// ================================================================
// 5D. Cancel → pending-cancel untuk SEMUA plan (monthly window/free + yearly)
//     pending-cancel = subscription aktif sampai next_payment → lalu berakhir
//     User tidak di-charge periode berikutnya
//
//     PERUBAHAN v2: Tambah guard untuk upgrade flow baru (add-to-cart)
//     Selain WCS switch detection, cek juga apakah ada plan lebih tinggi
//     yang baru aktif dalam 120 detik terakhir → ini cancel karena upgrade
// ================================================================
add_action('woocommerce_subscription_status_updated', 'phoenix_cancel_to_pending', 10, 3);
function phoenix_cancel_to_pending($subscription, $new_status, $old_status) {
    if ($new_status !== 'cancelled') return;
    if ($old_status === 'pending-cancel') return;
 
    // Static guard: prevent re-entry kalau update_status trigger hook ini lagi
    static $running = [];
    $sub_id = $subscription->get_id();
    if (isset($running[$sub_id])) return;
    $running[$sub_id] = true;
 
    // Guard 1: skip kalau ini cancel karena WCS switch/upgrade
    // Cek apakah ada subscription lain yang punya _subscription_switch ke sub ini
    global $wpdb;
    $switched = $wpdb->get_var($wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta}
         WHERE meta_key = '_subscription_switch'
         AND meta_value = %d
         LIMIT 1",
        $sub_id
    ));
    if ($switched) {
        unset($running[$sub_id]);
        return; // Cancel karena WCS upgrade — skip
    }
 
    // Skip addon & free
    $plan_hierarchy  = function_exists('phoenix_get_plan_hierarchy') ? phoenix_get_plan_hierarchy() : [30688=>1,11=>1,58=>2,61=>2,62=>2,76=>3,78=>3,79=>3];
    $cancelled_level = 0;
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) { unset($running[$sub_id]); return; }
        $n = strtolower($item->get_name());
        if (strpos($n, 'free') !== false || strpos($n, 'starter') !== false) { unset($running[$sub_id]); return; }
        if (in_array($item->get_product_id(), [11, 30688])) { unset($running[$sub_id]); return; }
        $pid = $item->get_product_id();
        if (isset($plan_hierarchy[$pid])) $cancelled_level = $plan_hierarchy[$pid];
    }
 
    // Guard 2: skip kalau ini cancel karena upgrade flow baru (add-to-cart)
    // Cek apakah ada subscription lebih tinggi yang baru aktif (< 120 detik)
    $user_id = $subscription->get_user_id();
    if (function_exists('wcs_get_users_subscriptions')) {
        foreach (wcs_get_users_subscriptions($user_id) as $other_sub) {
            if ($other_sub->get_id() === $sub_id) continue;
            if (!$other_sub->has_status('active')) continue;
            $start_time = $other_sub->get_time('start');
            if ((time() - $start_time) > 300) continue; // lebih dari 5 menit — bukan upgrade baru
            foreach ($other_sub->get_items() as $item) {
                if (has_term('add-on', 'product_cat', $item->get_product_id())) continue;
                $pid   = $item->get_product_id();
                $level = isset($plan_hierarchy[$pid]) ? $plan_hierarchy[$pid] : 0;
                if ($level > $cancelled_level) {
                    // Ada plan lebih tinggi yang baru aktif = cancel karena upgrade flow baru
                    unset($running[$sub_id]);
                    return;
                }
            }
        }
    }
 
    $billing_period = $subscription->get_billing_period();
    $interval       = (int) $subscription->get_billing_interval();
    $is_yearly      = ($billing_period === 'year') || ($billing_period === 'month' && $interval >= 12);
 
    if ($is_yearly) {
        $note = phoenix_text('prorate.note_cancel_yearly');
    } else {
        $window = phoenix_get_cancel_window($subscription);
        if ($window === 'locked') { unset($running[$sub_id]); return; }
        $note = phoenix_text('prorate.note_cancel_monthly');
    }
 
    try {
        $subscription->update_status('pending-cancel', $note);
    } catch (Exception $e) {
        error_log('phoenix_cancel_to_pending error (sub #' . $sub_id . '): ' . $e->getMessage());
    }
 
    unset($running[$sub_id]);
}
 
// ================================================================
// 5E. Yearly → allow cancel kapan saja
// ================================================================
add_filter('woocommerce_subscription_can_cancel', 'phoenix_yearly_allow_cancel', 10, 2);
function phoenix_yearly_allow_cancel($can_cancel, $subscription) {
    $billing_period = $subscription->get_billing_period();
    $interval       = (int) $subscription->get_billing_interval();
    $is_yearly      = ($billing_period === 'year') || ($billing_period === 'month' && $interval >= 12);
    if (!$is_yearly) return $can_cancel;
 
    // Skip addon & free
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) return false;
        $n = strtolower($item->get_name());
        if (strpos($n, 'free') !== false || strpos($n, 'starter') !== false) return $can_cancel;
    }
 
    return true; // Yearly boleh cancel kapan saja
}
 
// 6. Force auto renewal
add_filter('woocommerce_subscription_is_manual', 'force_auto_renewal', 10, 2);
function force_auto_renewal($is_manual, $subscription) { return false; }
 
// 7. Block addon untuk Free Plan
if (!function_exists('control_addon_access_by_plan')) {
    add_filter('woocommerce_is_purchasable', 'control_addon_access_by_plan', 10, 2);
    function control_addon_access_by_plan($purchasable, $product) {
        if (!function_exists('wcs_get_users_subscriptions')) return $purchasable;
        if (!has_term('add-on', 'product_cat', $product->get_id())) return $purchasable;
        $allowed_plans = get_post_meta($product->get_id(), '_addon_allowed_plans', true);
        if (!$allowed_plans) return $purchasable;
        $allowed = array_map('trim', explode(',', $allowed_plans));
        $subscriptions = wcs_get_users_subscriptions(get_current_user_id());
        foreach ($subscriptions as $sub) {
            if (!$sub->has_status('active')) continue;
            foreach ($sub->get_items() as $item) {
                $name = strtolower($item->get_name());
                foreach ($allowed as $plan) {
                    if (strpos($name, $plan) !== false) return $purchasable;
                }
            }
        }
        return false;
    }
}
 
/*// ================================================================
// HELPER FUNCTIONS — dipakai di Section 8, 12, 0E, dst
// ================================================================
if (!function_exists('phoenix_get_plan_hierarchy')) {
function phoenix_get_plan_hierarchy() {
    return [
        30688 => 1, 30689 => 1, 11 => 1,       // Free
        58 => 2, 61 => 2, 62 => 2, 22 => 2,    // Basic
        76 => 3, 78 => 3, 79 => 3, 33 => 3,    // Premium
    ];
}
}
 
if (!function_exists('phoenix_get_plan_level_from_item')) {
function phoenix_get_plan_level_from_item($item, $plan_hierarchy) {
    $pid   = $item->get_product_id();
    $level = isset($plan_hierarchy[$pid]) ? $plan_hierarchy[$pid] : 0;
    if ($level === 0) {
        $n = strtolower($item->get_name());
        if (strpos($n, 'premium') !== false || strpos($n, 'custom') !== false || strpos($n, 'byo') !== false) $level = 3;
        elseif (strpos($n, 'basic') !== false || strpos($n, 'standard') !== false) $level = 2;
        elseif (strpos($n, 'free') !== false || strpos($n, 'starter') !== false) $level = 1;
    }
    return $level;
}
}*/
 
 
// ================================================================
// 8. Auto cancel lower plan on upgrade + sync next_payment ke plan lama
//
//    PERUBAHAN v2: Support KEDUA metode deteksi upgrade:
//    - Metode lama: WCS switch (switch-subscription di URL/referer)
//    - Metode baru: add-to-cart (phoenix_upgrade_from_sub di WC session)
//    Sync next_payment sekarang ambil langsung dari old subscription object
// ================================================================
add_action('woocommerce_subscription_status_updated', 'auto_cancel_lower_plan_on_upgrade', 10, 3);
function auto_cancel_lower_plan_on_upgrade($subscription, $new_status, $old_status) {
    if ($new_status !== 'active') return;
    if (!function_exists('wcs_get_users_subscriptions')) return;
 
    static $running = [];
    $sub_id = $subscription->get_id();
    if (isset($running[$sub_id])) return;
    $running[$sub_id] = true;
 
    $plan_hierarchy = phoenix_get_plan_hierarchy();
    $new_plan_level = 0;
 
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) { unset($running[$sub_id]); return; }
        $new_plan_level = phoenix_get_plan_level_from_item($item, $plan_hierarchy);
    }
    if ($new_plan_level === 0) { unset($running[$sub_id]); return; }
 
    // ── Deteksi upgrade flow — support KEDUA metode ──────────────
    // Metode lama: WCS switch URL
    $is_switch_wcs = isset($_GET['switch-subscription']) ||
                     (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'switch-subscription') !== false);
 
    // Metode baru: add-to-cart dari upgrade-plan-page v3
    // Cek WC session dulu, fallback ke WP option (session tidak reliable dari AJAX)
    $user_id_s8               = $subscription->get_user_id();
    $upgrade_from_sub_session = WC()->session ? (int) WC()->session->get('phoenix_upgrade_from_sub') : 0;
    $upgrade_from_sub_option  = (int) get_option('_phoenix_upgrade_from_sub_' . $user_id_s8, 0);
    $upgrade_from_sub_val     = $upgrade_from_sub_session ?: $upgrade_from_sub_option;
    $is_switch_new            = ($upgrade_from_sub_val > 0);
 
    if (!$is_switch_wcs && !$is_switch_new) { unset($running[$sub_id]); return; }
 
    // Tentukan old subscription ID
    $switched_from_sub_id = 0;
    if ($is_switch_wcs) {
        $parent_order = wc_get_order($subscription->get_parent_id());
        if ($parent_order) {
            $switched_from_sub_id = (int) $parent_order->get_meta('_subscription_switch');
            if (!$switched_from_sub_id) {
                foreach ($parent_order->get_items() as $item) {
                    $sid = (int) $item->get_meta('_switched_subscription_id');
                    if ($sid) { $switched_from_sub_id = $sid; break; }
                }
            }
        }
    }
    if (!$switched_from_sub_id && $is_switch_new) {
        $switched_from_sub_id = $upgrade_from_sub_val;
        // JANGAN clear session di sini — section 21 (payment_complete) masih butuh
        // WP option akan di-delete oleh section 21 setelah selesai
    }
 
    // Ambil tenant untuk validasi same instance
    global $wpdb;
    $table  = $wpdb->prefix . 'wbssaas_tenants';
    $new_sub_id = $subscription->get_id();
 
    $tenant = $switched_from_sub_id
        ? $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE subscription_wc_id = %d LIMIT 1", $switched_from_sub_id))
        : $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE subscription_wc_id = %d LIMIT 1", $new_sub_id));
 
    $user_id  = $subscription->get_user_id();
    $all_subs = wcs_get_users_subscriptions($user_id);
 
    // Sync next_payment + prorate extend credit_days
    // Filosofi: user bayar FULL PRICE new plan, next_payment di-extend sebagai kompensasi
    // sisa hari old plan yang belum terpakai.
    $old_next_payment = null;
    if ($switched_from_sub_id) {
        $old_sub  = wcs_get_subscription($switched_from_sub_id);
        $old_next = $old_sub ? $old_sub->get_date('next_payment') : null;
        if ($old_next) $old_next_payment = $old_next;
    }

    // Cek apakah old plan adalah Free — Free plan tidak dapat prorate extend
    $old_sub_is_free = false;
    if (isset($old_sub) && $old_sub) {
        foreach ($old_sub->get_items() as $_oi) {
            $old_sub_is_free = (
                $old_sub->get_total() == 0 ||
                in_array($_oi->get_product_id(), [30688, 30689, 11]) ||
                strpos(strtolower($_oi->get_name()), 'free') !== false ||
                strpos(strtolower($_oi->get_name()), 'starter') !== false
            );
            break;
        }
    }

    if ($old_next_payment && !$old_sub_is_free) {
        // ── Prorate extend: value-based (Opsi B) ─────────────────────────────
        // Formula:
        //   old_price        = harga recurring old plan
        //   old_period_days  = total hari 1 periode old plan
        //   days_remaining   = sisa hari old plan
        //   remaining_value  = old_price × (days_remaining / old_period_days)
        //   new_daily_rate   = new_price / new_period_days
        //   extended_days    = remaining_value / new_daily_rate
        //   new_next_payment = today + new_period_days + extended_days
        //
        // Contoh: Basic Monthly ($65/30d) → Premium Monthly ($110/30d), upgrade hari ke-1
        //   days_remaining  = 29, remaining_value = $65 × (29/30) = $62.83
        //   new_daily_rate  = $110/30 = $3.67/day
        //   extended_days   = $62.83 / $3.67 = 17.1 ≈ 17 days
        //   new_next_payment = today + 30 + 17 = 47 days
        //
        // Contoh: Basic Yearly ($650/365d) → Premium Yearly ($995/365d), upgrade hari ke-1
        //   days_remaining  = 364, remaining_value = $650 × (364/365) = $648.22
        //   new_daily_rate  = $995/365 = $2.726/day
        //   extended_days   = $648.22 / $2.726 = 237.8 ≈ 238 days
        //   new_next_payment = today + 365 + 238 = 603 days

        $now         = current_time('timestamp');
        $old_np_ts   = strtotime($old_next_payment);

        // Hitung new_period_days
        $new_period_days = 365;
        $new_bp          = $subscription->get_billing_period();
        $new_bi          = max(1, (int) $subscription->get_billing_interval());
        if ($new_bp === 'month') {
            $new_period_days = (int) round((strtotime('+' . $new_bi . ' month', $now) - $now) / DAY_IN_SECONDS);
        } elseif ($new_bp === 'year') {
            $new_period_days = 365 * $new_bi;
        }

        // Hitung extended_days via value-based conversion
        $extended_days = 0;
        $credit_note   = '';

        if (isset($old_sub) && $old_sub) {
            $days_remaining = max(0, (int) floor(($old_np_ts - $now) / DAY_IN_SECONDS));

            if ($days_remaining > 0) {
                // Old plan price & period
                $old_price       = (float) $old_sub->get_subtotal();
                $old_period_days = phoenix_prorate_get_period_days($old_sub);

                // New plan price
                $new_price = (float) $subscription->get_subtotal();
                if ($new_price <= 0) {
                    // Fallback: ambil dari line items
                    foreach ($subscription->get_items() as $_ni) {
                        $new_price = (float) $_ni->get_subtotal();
                        break;
                    }
                }

                if ($old_price > 0 && $new_price > 0 && $old_period_days > 0 && $new_period_days > 0) {
                    $remaining_value = $old_price * ($days_remaining / $old_period_days);
                    $new_daily_rate  = $new_price / $new_period_days;
                    $extended_days   = (int) floor($remaining_value / $new_daily_rate);

                    $credit_note = sprintf(
                        '[Prorate Extend] old=%s days_remaining=%d remaining_value=%.2f new_daily_rate=%.4f extended_days=%d',
                        $old_sub->get_id(), $days_remaining, $remaining_value, $new_daily_rate, $extended_days
                    );
                    error_log('[phoenix_upgrade_prorate] ' . $credit_note);
                }
            }
        }

        // new_next_payment = today + new_period_days + extended_days
        $final_np_ts = $now + (($new_period_days + $extended_days) * DAY_IN_SECONDS);
        $final_np    = date('Y-m-d H:i:s', $final_np_ts);

        try {
            $subscription->update_dates(['next_payment' => $final_np]);
            $subscription->save();
            $note = sprintf(
                'Prorate extend: new plan %d days + %d extended days (value-based) = next payment %s',
                $new_period_days,
                $extended_days,
                date('d M Y', $final_np_ts)
            );
            $subscription->add_order_note($note);
            if ($credit_note) $subscription->add_order_note($credit_note);
        } catch (Exception $e) {
            error_log('Sync next_payment error: ' . $e->getMessage());
        }
    }
 
    // Cancel lower plan — HANYA instance yang sama
    foreach ($all_subs as $sub) {
        if ($sub->get_id() === $new_sub_id) continue;
        if (!$sub->has_status('active')) continue;
 
        foreach ($sub->get_items() as $item) {
            if (has_term('add-on', 'product_cat', $item->get_product_id())) continue;
 
            $level = phoenix_get_plan_level_from_item($item, $plan_hierarchy);
            if ($level <= 0 || $level >= $new_plan_level) continue;
 
            // Validasi same instance via tenants table
            if ($tenant) {
                $same_instance = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM $table WHERE subscription_wc_id = %d AND id = %d",
                    $sub->get_id(), $tenant->id
                ));
                if (!$same_instance) continue; // Beda instance — SKIP
            }
 
            $pid = $item->get_product_id();
            $variation_id = (int) $sub->get_meta('_variation_id');
            $is_free_plan = (
                $sub->get_total() == 0 ||
                in_array($pid, [30688, 30689, 11]) ||
                in_array($variation_id, [30689])
            );
 
            if ($is_free_plan) {
                try {
                    wp_update_post(['ID' => $sub->get_id(), 'post_status' => 'wc-cancelled']);
                    $sub->add_order_note('Auto-cancelled: upgraded to higher plan (same instance).');
                    $sub->save();
                } catch (Exception $e) {
                    error_log('Force cancel error sub #' . $sub->get_id() . ': ' . $e->getMessage());
                }
            } else {
                $sub->update_status('cancelled', 'Auto-cancelled: upgraded to higher plan (same instance).');
            }
            break;
        }
    }
 
    unset($running[$sub_id]);
}
 
// 9. Hide related products & breadcrumb
add_filter('woocommerce_related_products', 'hide_related_for_subscription', 10, 3);
function hide_related_for_subscription($related_posts, $product_id, $args) {
    if (has_term('subscription', 'product_cat', $product_id) ||
        has_term('add-on', 'product_cat', $product_id)) return [];
    return $related_posts;
}
 
add_filter('woocommerce_breadcrumb_defaults', 'hide_breadcrumb_for_subscription');
function hide_breadcrumb_for_subscription($defaults) {
    if (is_product()) {
        global $post;
        if (has_term('subscription', 'product_cat', $post->ID) ||
            has_term('add-on', 'product_cat', $post->ID)) {
            add_filter('woocommerce_breadcrumb_home_url', '__return_false');
            $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" style="display:none">';
        }
    }
    return $defaults;
}
 
// 10. Auto-complete Free Plan order
add_action('woocommerce_checkout_subscription_created', function ($subscription, $order) {
    foreach ($subscription->get_items() as $item) {
        $n = strtolower($item->get_name());
        if ($item->get_product_id() == 30688 || strpos($n, 'free') !== false || strpos($n, 'starter') !== false) {
            if ($order && $order instanceof WC_Order && $order->get_status() !== 'completed') {
                $order->update_status('completed', 'Free Plan order automatically completed.');
            }
        }
    }
}, 10, 2);
 
/* 11. Force qty 1 Free Plan — dinonaktifkan
...
*/
 
// Hide tombol Upgrade/Switch dari main plan di view-subscription — upgrade lewat Workspaces
add_filter('wcs_view_subscription_actions', 'hide_upgrade_button_from_main_plan', 10, 2);
function hide_upgrade_button_from_main_plan($actions, $subscription) {
    unset($actions['switch']);
    foreach ($actions as $key => $action) {
        if (isset($action['url']) && strpos($action['url'], 'switch-subscription') !== false) {
            unset($actions[$key]);
        }
    }
    return $actions;
}

// 12. Fix switch URL + upgrade picker — disabled, upgrade via workspaces page
add_action('wp_footer', 'fix_free_plan_switch_url');
function fix_free_plan_switch_url() {
    return; // CTA upgrade dihide — customer upgrade lewat Workspaces
    if (!is_wc_endpoint_url('view-subscription')) return;
    if (!function_exists('wcs_get_users_subscriptions')) return;
 
    global $wp;
    $sub_id = isset($wp->query_vars['view-subscription']) ? (int)$wp->query_vars['view-subscription'] : 0;
    if (!$sub_id) return;
 
    $subscription = wcs_get_subscription($sub_id);
    if (!$subscription || !$subscription->has_status('active')) return;
    if ($subscription->get_user_id() != get_current_user_id()) return;
 
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) return;
    }
 
    // Cek highest active plan user
    $plan_hierarchy    = phoenix_get_plan_hierarchy();
    $current_sub_level = 0;
    foreach ($subscription->get_items() as $_item) {
        $current_sub_level = phoenix_get_plan_level_from_item($_item, $plan_hierarchy);
        if ($current_sub_level > 0) break;
    }
 
    $user_highest_level = 0;
    $all_user_subs      = wcs_get_users_subscriptions(get_current_user_id());
    foreach ($all_user_subs as $_sub) {
        if (!$_sub->has_status('active')) continue;
        if ($_sub->get_id() === $sub_id) continue;
        foreach ($_sub->get_items() as $_item) {
            if (has_term('add-on', 'product_cat', $_item->get_product_id())) continue;
            $lvl = phoenix_get_plan_level_from_item($_item, $plan_hierarchy);
            if ($lvl > $user_highest_level) $user_highest_level = $lvl;
        }
    }
 
    if ($user_highest_level > $current_sub_level) return;
    if ($current_sub_level >= 3) return;
 
    // ✅ WCML: ambil currency aktif dan symbol
    $current_currency = get_woocommerce_currency();
    $currency_symbol  = get_woocommerce_currency_symbol($current_currency);
 
    foreach ($subscription->get_items() as $item_id => $item) {
        $name = strtolower($item->get_name());
        $pid  = $item->get_product_id();
 
        $is_free  = strpos($name, 'free') !== false || strpos($name, 'starter') !== false
                    || in_array($pid, [11, 30688, 30689]);
        $is_basic = strpos($name, 'basic') !== false || strpos($name, 'standard') !== false
                    || in_array($pid, [22, 58, 61, 62]);
 
        if ($is_free) {
 
            // ✅ Ambil harga Basic dan Premium per currency
            $basic_monthly_price  = phoenix_get_variation_price(61, $current_currency);
            $basic_yearly_price   = phoenix_get_variation_price(62, $current_currency);
            $premium_monthly_price = phoenix_get_variation_price(78, $current_currency);
            $premium_yearly_price  = phoenix_get_variation_price(79, $current_currency);
 
            // Hitung per bulan untuk yearly
            $basic_yearly_monthly   = $basic_yearly_price > 0 ? $basic_yearly_price / 12 : 0;
            $premium_yearly_monthly = $premium_yearly_price > 0 ? $premium_yearly_price / 12 : 0;
 
            // Hitung badge savings %
            $basic_save_pct   = ($basic_monthly_price > 0 && $basic_yearly_monthly > 0)
                ? round((1 - ($basic_yearly_monthly / $basic_monthly_price)) * 100) : 0;
            $premium_save_pct = ($premium_monthly_price > 0 && $premium_yearly_monthly > 0)
                ? round((1 - ($premium_yearly_monthly / $premium_monthly_price)) * 100) : 0;
 
            $basic_url   = get_permalink(58);
            $premium_url = get_permalink(76);
 
            $basic_yearly_price_str = $currency_symbol . number_format($basic_yearly_monthly, 2) . '/mo';
 
			$basic_vars = [
				'Monthly' => [
					'price' => $currency_symbol . number_format($basic_monthly_price, 2) . '/mo',
					'url'   => $basic_url . '?variation_id=61&attribute_payment=Monthly',
				],
				'Yearly' => [
					'price'    => $basic_yearly_price_str . ' (billed annually)',
					'url'      => $basic_url . '?variation_id=62&attribute_payment=Yearly',
					'save_pct' => $basic_save_pct,
				],
			];
 
			$premium_yearly_price_str = $currency_symbol . number_format($premium_yearly_monthly, 2) . '/mo';
 
			$premium_vars = [
				'Monthly' => [
					'price' => $currency_symbol . number_format($premium_monthly_price, 2) . '/mo',
					'url'   => $premium_url . '?variation_id=78&attribute_payment=Monthly',
				],
				'Yearly' => [
					'price'    => $premium_yearly_price_str . ' (billed annually)',
					'url'      => $premium_url . '?variation_id=79&attribute_payment=Yearly',
					'save_pct' => $premium_save_pct,
				],
			];
            ?>
            <style>
            .phoenix-upgrade-picker{margin:12px 0}
            .phoenix-upgrade-cards{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px}
            .phoenix-upgrade-card{display:flex;flex-direction:column;border:2px solid #e0e0e0;border-radius:8px;padding:10px 14px;text-decoration:none;color:#333;background:#fff;transition:all .2s;min-width:130px;cursor:pointer}
            .phoenix-upgrade-card:hover{border-color:#3498db;background:#f0f7ff;color:#333}
            .phoenix-upgrade-card.yearly{border-color:#27ae60}
            .phoenix-upgrade-card.yearly:hover{background:#f0fff4}
            .phoenix-upgrade-card .card-label{font-weight:bold;font-size:13px}
            .phoenix-upgrade-card .card-price{font-size:12px;color:#666;margin-top:2px}
            .phoenix-upgrade-card .card-badge{font-size:10px;background:#27ae60;color:#fff;border-radius:3px;padding:1px 5px;margin-top:4px;align-self:flex-start}
            .phoenix-plan-label{font-size:14px;font-weight:bold;margin:10px 0 4px}
            .phoenix-plan-basic .phoenix-plan-label{color:#3498db}
            .phoenix-plan-premium .phoenix-plan-label{color:#f39c12}
            </style>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var basicVars   = <?php echo json_encode($basic_vars); ?>;
                var premiumVars = <?php echo json_encode($premium_vars); ?>;
 
                function makePicker() {
                    var picker = document.createElement('div');
                    picker.className = 'phoenix-upgrade-picker';
 
                    if (Object.keys(basicVars).length > 0) {
                        var bs = document.createElement('div');
                        bs.className = 'phoenix-plan-basic';
                        bs.innerHTML = '<div class="phoenix-plan-label">📦 Basic Plan</div>';
                        var bc = document.createElement('div');
                        bc.className = 'phoenix-upgrade-cards';
                        ['Monthly','Yearly'].forEach(function(p) {
                            if (!basicVars[p]) return;
                            var card = document.createElement('a');
                            card.className = 'phoenix-upgrade-card'+(p==='Yearly'?' yearly':'');
                            card.href = basicVars[p].url;
                            var badge = (p==='Yearly' && basicVars[p].save_pct > 0)
                                ? '<span class="card-badge">Save '+basicVars[p].save_pct+'%</span>' : '';
                            card.innerHTML = '<span class="card-label">📅 '+p+'</span>'
                                +'<span class="card-price">'+basicVars[p].price+'</span>'+badge;
                            bc.appendChild(card);
                        });
                        bs.appendChild(bc);
                        picker.appendChild(bs);
                    }
 
                    if (Object.keys(premiumVars).length > 0) {
                        var ps = document.createElement('div');
                        ps.className = 'phoenix-plan-premium';
                        ps.innerHTML = '<div class="phoenix-plan-label">⭐ Premium Plan</div>';
                        var pc = document.createElement('div');
                        pc.className = 'phoenix-upgrade-cards';
                        ['Monthly','Yearly'].forEach(function(p) {
                            if (!premiumVars[p]) return;
                            var card = document.createElement('a');
                            card.className = 'phoenix-upgrade-card'+(p==='Yearly'?' yearly':'');
                            card.href = premiumVars[p].url;
                            var badge = (p==='Yearly' && premiumVars[p].save_pct > 0)
                                ? '<span class="card-badge">Save '+premiumVars[p].save_pct+'%</span>' : '';
                            card.innerHTML = '<span class="card-label">📅 '+p+'</span>'
                                +'<span class="card-price">'+premiumVars[p].price+'</span>'+badge;
                            pc.appendChild(card);
                        });
                        ps.appendChild(pc);
                        picker.appendChild(ps);
                    }
                    return picker;
                }
 
                document.querySelectorAll('a[href*="switch-subscription"]').forEach(function(l){l.remove()});
                var t = document.querySelector('table.shop_table.subscription_details,table.shop_table.woocommerce-table');
                if (t && t.parentNode) t.parentNode.insertBefore(makePicker(), t.nextSibling);
                else { var w=document.querySelector('.woocommerce'); if(w) w.appendChild(makePicker()); }
            });
            </script>
            <?php
            break;
 
        } elseif ($is_basic) {
 
            $current_period   = $subscription->get_billing_period();
            $current_interval = $subscription->get_billing_interval();
            $current_yearly   = ($current_period === 'year') || ($current_period === 'month' && (int)$current_interval >= 12);
 
            // ✅ Ambil harga Premium per currency
            $premium_monthly_price  = phoenix_get_variation_price(78, $current_currency);
            $premium_yearly_price   = phoenix_get_variation_price(79, $current_currency);
            $premium_yearly_monthly = $premium_yearly_price > 0 ? $premium_yearly_price / 12 : 0;
            $premium_save_pct       = ($premium_monthly_price > 0 && $premium_yearly_monthly > 0)
                ? round((1 - ($premium_yearly_monthly / $premium_monthly_price)) * 100) : 0;
 
            $vid        = $current_yearly ? 79 : 78;
			$save_pct   = $current_yearly ? $premium_save_pct : 0;
			$display_price = $current_yearly
				? $currency_symbol . number_format($premium_yearly_monthly, 2) . '/mo (billed annually)'
				: $currency_symbol . number_format($premium_monthly_price, 2) . '/mo';
 
			$premium_vars = [
				'price'    => $display_price,
				'url'      => get_permalink(get_page_by_path('upgrade-plan')) . '?sub_id=' . $sub_id,
				'save_pct' => $save_pct,
				'yearly'   => $current_yearly,
			];
            ?>
            <style>
            .phoenix-upgrade-picker{margin:12px 0}
            .phoenix-upgrade-cards{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px}
            .phoenix-upgrade-card{display:flex;flex-direction:column;border:2px solid #e0e0e0;border-radius:8px;padding:10px 14px;text-decoration:none;color:#333;background:#fff;transition:all .2s;min-width:130px}
            .phoenix-upgrade-card:hover{border-color:#f39c12;background:#fffbf0;color:#333}
            .phoenix-upgrade-card.yearly{border-color:#27ae60}
            .phoenix-upgrade-card.yearly:hover{background:#f0fff4}
            .phoenix-upgrade-card.matched{border:2px solid #3498db}
            .phoenix-upgrade-card .card-label{font-weight:bold;font-size:13px}
            .phoenix-upgrade-card .card-price{font-size:12px;color:#666;margin-top:2px}
            .phoenix-upgrade-card .card-badge{font-size:10px;background:#27ae60;color:#fff;border-radius:3px;padding:1px 5px;margin-top:4px;align-self:flex-start}
            .phoenix-upgrade-card .card-match{font-size:10px;background:#3498db;color:#fff;border-radius:3px;padding:1px 5px;margin-top:4px;align-self:flex-start}
            .phoenix-plan-label{font-size:14px;font-weight:bold;color:#f39c12;margin-bottom:6px}
            </style>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var premiumVar = <?php echo json_encode($premium_vars); ?>;
                function makePicker() {
                    var picker = document.createElement('div');
                    picker.className = 'phoenix-upgrade-picker';
                    picker.innerHTML = '<div class="phoenix-plan-label">⭐ Upgrade to Premium</div>';
                    var cards = document.createElement('div');
                    cards.className = 'phoenix-upgrade-cards';
                    var card = document.createElement('a');
                    card.className = 'phoenix-upgrade-card matched'+(premiumVar.yearly?' yearly':'');
                    card.href = premiumVar.url;
                    var badge = (premiumVar.save_pct > 0)
                        ? '<span class="card-badge">Save '+premiumVar.save_pct+'%</span>' : '';
                    card.innerHTML = '<span class="card-label">📅 '+(premiumVar.yearly?'Yearly':'Monthly')+'</span>'
                        +'<span class="card-price">'+premiumVar.price+'</span>'+badge
                        +'<span class="card-match">Matches your plan</span>';
                    cards.appendChild(card);
                    picker.appendChild(cards);
                    return picker;
                }
                document.querySelectorAll('a[href*="switch-subscription"]').forEach(function(l){l.remove()});
                var t = document.querySelector('table.shop_table.subscription_details,table.shop_table.woocommerce-table');
                if (t && t.parentNode) t.parentNode.insertBefore(makePicker(), t.nextSibling);
                else { var w=document.querySelector('.woocommerce'); if(w) w.appendChild(makePicker()); }
            });
            </script>
            <?php
            break;
        }
    }
}
 
// 13. Auto-check save payment checkbox
add_action('wp_footer', 'auto_check_save_payment_checkbox');
function auto_check_save_payment_checkbox() {
    if (!is_checkout()) return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function autoCheckSavePayment() {
            var selectors = [
                '.wc-block-components-payment-methods__save-card-info input[type="checkbox"]',
                '.wc-block-components-checkbox__input',
                '#checkbox-control-1',
            ];
            selectors.forEach(function(selector) {
                document.querySelectorAll(selector).forEach(function(checkbox) {
                    if (!checkbox.checked) {
                        checkbox.checked = true;
                        checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                        checkbox.dispatchEvent(new Event('click', { bubbles: true }));
                        var nativeInputValueSetter = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, 'checked').set;
                        nativeInputValueSetter.call(checkbox, true);
                        checkbox.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                });
            });
        }
        autoCheckSavePayment();
        var observer = new MutationObserver(function() { autoCheckSavePayment(); });
        observer.observe(document.body, { childList: true, subtree: true });
        setTimeout(autoCheckSavePayment, 1000);
        setTimeout(autoCheckSavePayment, 2000);
        setTimeout(autoCheckSavePayment, 4000);
    });
    </script>
    <?php
}
 
// 14. Hide save payment checkbox
add_action('wp_head', 'hide_save_payment_checkbox_css');
function hide_save_payment_checkbox_css() {
    if (!is_checkout()) return;
    ?>
    <style>.wc-block-components-payment-methods__save-card-info { display: none !important; }</style>
    <?php
}
 
// 15. Track highest plan level (tetap jalan untuk audit trail)
add_action('woocommerce_subscription_status_updated', 'track_highest_plan_level', 10, 3);
function track_highest_plan_level($subscription, $new_status, $old_status) {
    if ($new_status !== 'active') return;
    $plan_hierarchy = function_exists('phoenix_get_plan_hierarchy')
        ? phoenix_get_plan_hierarchy()
        : [30688 => 1, 11 => 1, 58 => 2, 61 => 2, 62 => 2, 22 => 2, 76 => 3, 78 => 3, 79 => 3, 33 => 3];
    $user_id = $subscription->get_user_id();
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) return;
        $pid = $item->get_product_id();
        $level = isset($plan_hierarchy[$pid]) ? $plan_hierarchy[$pid] : 0;
        if ($level === 0) {
            $n = strtolower($item->get_name());
            if (strpos($n, 'premium') !== false || strpos($n, 'custom') !== false || strpos($n, 'byo') !== false) $level = 3;
            elseif (strpos($n, 'basic') !== false || strpos($n, 'standard') !== false) $level = 2;
            elseif (strpos($n, 'free') !== false || strpos($n, 'starter') !== false) $level = 1;
        }
        if ($level === 0) continue;
        $saved_level = (int) get_user_meta($user_id, '_highest_plan_level', true);
        if ($level > $saved_level) update_user_meta($user_id, '_highest_plan_level', $level);
 
        // Simpan commitment_start_date saat pertama kali upgrade ke paid (level >= 2)
        if ($level >= 2 && !$subscription->get_meta('_commitment_start_date')) {
            $sub_start = $subscription->get_time('start');
            if ($sub_start) {
                $subscription->update_meta_data('_commitment_start_date', $sub_start);
                $subscription->save();
            }
        }
    }
}
 
add_filter('woocommerce_add_to_cart_validation', 'block_lower_plan_purchase', 10, 3);
function block_lower_plan_purchase($passed, $product_id, $quantity) {
    if (!is_user_logged_in()) return $passed;
 
    // Tidak ada upgrade_subscription = new instance = bebas
    if (!isset($_GET['upgrade_subscription']) && !isset($_GET['switch-subscription'])) {
        return $passed;
    }
 
    $sub_id = isset($_GET['upgrade_subscription'])
        ? absint($_GET['upgrade_subscription'])
        : 0;
 
    if (!$sub_id) return $passed;
 
    $user_id = get_current_user_id();
    if (!function_exists('phoenix_user_owns_subscription')
        || !phoenix_user_owns_subscription($sub_id, $user_id)) {
        return $passed;
    }
 
    $plan_hierarchy = phoenix_get_plan_hierarchy();
    $product        = wc_get_product($product_id);
    $parent_id      = $product ? ($product->get_parent_id() ?: $product_id) : $product_id;
    if (!isset($plan_hierarchy[$parent_id])) return $passed;
 
    $attempting_level = $plan_hierarchy[$parent_id];
    $current_sub      = wcs_get_subscription($sub_id);
    if (!$current_sub) return $passed;
 
    $current_level = function_exists('phoenix_get_subscription_plan_level')
        ? phoenix_get_subscription_plan_level($current_sub)
        : 0;
 
    if ($attempting_level < $current_level) {
        wc_add_notice('You cannot downgrade an existing instance.', 'error');
        return false;
    }
 
    return $passed;
}
 
// 16. Auto-complete order subscription
add_action('woocommerce_payment_complete', 'auto_complete_subscription_order', 10, 1);
function auto_complete_subscription_order($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;
    $has_subscription = false;
    foreach ($order->get_items() as $item) {
        $product = $item->get_product();
        if ($product && $product->is_type(['subscription', 'variable-subscription', 'subscription_variation'])) {
            $has_subscription = true; break;
        }
    }
    if (!$has_subscription) return;
    if ($order->get_status() === 'processing') {
        $order->update_status('completed', 'Auto-completed: virtual subscription product.');
    }
}
 
// ================================================================
// 17. Cancel addon otomatis saat main plan cancel (no refund)
// ================================================================
 
// ================================================================
// HELPER: Cek apakah addon subscription milik instance main plan tertentu
// Flow: addon_sub → order → GF entry field 1 → tenant_uuid → wbssaas_tenants → ALL sub IDs untuk tenant ini
// FIX: Setelah upgrade Basic→Premium, subscription_wc_id di DB berubah ke Premium ID.
//      Fungsi ini sekarang cek SEMUA rows dengan tenant_uuid yang sama (semua historical sub IDs)
//      sehingga addon tetap ter-link ke main plan meskipun sudah di-upgrade.
// ================================================================
if (!function_exists('phoenix_addon_belongs_to_main_plan')) {
    function phoenix_addon_belongs_to_main_plan($addon_sub_id, $main_plan_sub_id) {
        global $wpdb;
 
        $addon_sub = wcs_get_subscription($addon_sub_id);
        if (!$addon_sub) return false;
 
        $order_id = $addon_sub->get_parent_id();
        if (!$order_id) return false;
 
        // Cari tenant_uuid dari order item meta
        $tenant_uuid = '';
        $order = wc_get_order($order_id);
        if ($order) {
            foreach ($order->get_items() as $item) {
                foreach ($item->get_meta_data() as $m) {
                    $v = (string)$m->value;
                    if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $v)) {
                        $tenant_uuid = $v;
                        break 2;
                    }
                }
            }
        }
 
        // Fallback: cari dari gf_entry_meta langsung
        if (!$tenant_uuid) {
            $tenant_uuid = $wpdb->get_var($wpdb->prepare(
                "SELECT em.meta_value
                 FROM {$wpdb->prefix}gf_entry_meta em
                 INNER JOIN {$wpdb->prefix}gf_entry e ON e.id = em.entry_id
                 WHERE e.form_id = 64
                   AND e.id IN (
                       SELECT entry_id FROM {$wpdb->prefix}gf_entry_meta
                       WHERE meta_key = 'woocommerce_order_number' AND meta_value = %s
                   )
                   AND em.meta_key = '1'
                 LIMIT 1",
                (string)$order_id
            ));
        }
 
        if (!$tenant_uuid) return false;
 
        // FIX: Ambil SEMUA subscription_wc_id untuk tenant ini
        $all_linked_sub_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT subscription_wc_id FROM {$wpdb->prefix}wbssaas_tenants
             WHERE tenant_uuid = %s",
            $tenant_uuid
        ));
 
        if (empty($all_linked_sub_ids)) return false;
 
        return in_array((int)$main_plan_sub_id, array_map('intval', $all_linked_sub_ids));
    }
}
 
add_action('woocommerce_subscription_status_updated', 'cancel_addons_on_plan_cancel', 10, 3);
function cancel_addons_on_plan_cancel($subscription, $new_status, $old_status) {
    if ($new_status !== 'cancelled') return;
    if (!function_exists('wcs_get_users_subscriptions')) return;
 
    static $running = [];
    $sub_id = $subscription->get_id();
    if (isset($running[$sub_id])) return;
    $running[$sub_id] = true;
 
    $is_main_plan = false;
    $cancelled_level = 0;
    $plan_hierarchy = function_exists('phoenix_get_plan_hierarchy')
        ? phoenix_get_plan_hierarchy()
        : [30688 => 1, 11 => 1, 58 => 2, 61 => 2, 62 => 2, 22 => 2, 76 => 3, 78 => 3, 79 => 3, 33 => 3];
 
    foreach ($subscription->get_items() as $item) {
        $n = strtolower($item->get_name());
        if (has_term('add-on', 'product_cat', $item->get_product_id())) { unset($running[$sub_id]); return; }
 
        $pid = $item->get_product_id();
        $level = isset($plan_hierarchy[$pid]) ? $plan_hierarchy[$pid] : 0;
        if ($level === 0) {
            if (strpos($n, 'premium') !== false || strpos($n, 'custom') !== false || strpos($n, 'byo') !== false) $level = 3;
            elseif (strpos($n, 'basic') !== false || strpos($n, 'standard') !== false) $level = 2;
            elseif (strpos($n, 'free') !== false || strpos($n, 'starter') !== false) $level = 1;
        }
 
        if (strpos($n, 'basic') !== false || strpos($n, 'standard') !== false ||
            strpos($n, 'premium') !== false || strpos($n, 'custom') !== false ||
            strpos($n, 'byo') !== false || in_array($pid, [58, 22, 76, 33])) {
            $is_main_plan = true;
        }
 
        if ($level > 0) $cancelled_level = $level;
    }
 
    if (!$is_main_plan) { unset($running[$sub_id]); return; }
 
    $user_id  = $subscription->get_user_id();
    $all_subs = wcs_get_users_subscriptions($user_id);
 
    // ✅ Cek apakah ada plan aktif lebih tinggi → ini cancel karena upgrade → SKIP addon cancel
    foreach ($all_subs as $sub) {
        if ($sub->get_id() === $subscription->get_id()) continue;
        if (!$sub->has_status('active')) continue;
 
        foreach ($sub->get_items() as $item) {
            if (has_term('add-on', 'product_cat', $item->get_product_id())) continue;
 
            $pid = $item->get_product_id();
            $level = isset($plan_hierarchy[$pid]) ? $plan_hierarchy[$pid] : 0;
            if ($level === 0) {
                $n = strtolower($item->get_name());
                if (strpos($n, 'premium') !== false || strpos($n, 'custom') !== false || strpos($n, 'byo') !== false) $level = 3;
                elseif (strpos($n, 'basic') !== false || strpos($n, 'standard') !== false) $level = 2;
                elseif (strpos($n, 'free') !== false || strpos($n, 'starter') !== false) $level = 1;
            }
 
            if ($level > $cancelled_level) {
                $subscription->add_order_note(
                    'Addon NOT cancelled — detected active higher plan (level ' . $level . '). This cancel was due to upgrade.'
                );
                unset($running[$sub_id]);
                return;
            }
        }
    }
 
    // Tidak ada plan lebih tinggi = manual cancel = addon ikut cancel
    foreach ($all_subs as $sub) {
        if ($sub->get_id() === $subscription->get_id()) continue;
        if (!$sub->has_status('active')) continue;
        foreach ($sub->get_items() as $item) {
            if (!has_term('add-on', 'product_cat', $item->get_product_id())) continue;
            if (!phoenix_addon_belongs_to_main_plan($sub->get_id(), $subscription->get_id())) {
                continue;
            }
            $sub->update_status('cancelled', 'Auto-cancelled: main plan cancelled (same instance).');
            break;
        }
    }
 
    unset($running[$sub_id]);
}
 
// ================================================================
// 18. On-hold main plan → addon ikut on-hold, aktif lagi → addon aktif
// ================================================================
add_action('woocommerce_subscription_status_updated', 'sync_addon_onhold_status', 10, 3);
function sync_addon_onhold_status($subscription, $new_status, $old_status) {
    if (!in_array($new_status, ['on-hold', 'active', 'pending-cancel'])) return;
    if (!function_exists('wcs_get_users_subscriptions')) return;
 
    static $running = [];
    $sub_id = $subscription->get_id();
    if (isset($running[$sub_id])) return;
    $running[$sub_id] = true;
 
    $is_main_plan = false;
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) { unset($running[$sub_id]); return; }
        $n = strtolower($item->get_name());
        if (strpos($n, 'basic') !== false || strpos($n, 'standard') !== false ||
            strpos($n, 'premium') !== false || strpos($n, 'custom') !== false ||
            strpos($n, 'byo') !== false || in_array($item->get_product_id(), [58, 22, 76, 33])) {
            $is_main_plan = true; break;
        }
    }
    if (!$is_main_plan) { unset($running[$sub_id]); return; }
 
    $user_id  = $subscription->get_user_id();
    $all_subs = wcs_get_users_subscriptions($user_id);
 
    foreach ($all_subs as $sub) {
        if ($sub->get_id() === $subscription->get_id()) continue;
        foreach ($sub->get_items() as $item) {
            if (!has_term('add-on', 'product_cat', $item->get_product_id())) continue;
            if (!phoenix_addon_belongs_to_main_plan($sub->get_id(), $subscription->get_id())) {
                continue;
            }
            if ($new_status === 'on-hold' && $sub->has_status('active')) {
                $sub->update_status('on-hold', 'Auto on-hold: main plan payment failed (same instance).');
            } elseif ($new_status === 'active' && $sub->has_status(['on-hold', 'pending-cancel'])) {
                $sub->update_status('active', 'Auto reactivated: main plan reactivated (same instance).');
            } elseif ($new_status === 'pending-cancel' && $sub->has_status('active')) {
                $sub->update_status('pending-cancel', 'Auto pending-cancel: main plan cancelled by user (same instance).');
            }
            break;
        }
    }
 
    unset($running[$sub_id]);
}
 
// ================================================================
// 19. Upgrade main plan → addon TETAP AKTIF, next_payment TIDAK DIUBAH
//     Period lock: saat upgrade, period tidak boleh berubah (handled di prefill snippet)
//     Yang di-handle di sini: TIDAK cancel addon, tidak sync next_payment
//     Addon ikut main plan secara status saja (via section 17 & 18)
// ================================================================
add_action('woocommerce_subscription_status_updated', 'sync_addon_on_plan_upgrade', 10, 3);
function sync_addon_on_plan_upgrade($subscription, $new_status, $old_status) {
    // Intentionally left minimal — addon tidak perlu di-cancel atau sync saat upgrade
    // Addon tetap aktif dengan next_payment mereka sendiri
    // Main plan baru (Premium) next_payment akan di-sync ke yang lama via switch flow
    if ($new_status !== 'active') return;
    if (!function_exists('wcs_get_users_subscriptions')) return;
 
    static $running = [];
    $sub_id = $subscription->get_id();
    if (isset($running[$sub_id])) return;
    $running[$sub_id] = true;
 
    $plan_hierarchy = [30688 => 1, 11 => 1, 58 => 2, 22 => 2, 76 => 3, 33 => 3];
    $new_level = 0;
 
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) { unset($running[$sub_id]); return; }
        $pid = $item->get_product_id();
        if (isset($plan_hierarchy[$pid])) { $new_level = $plan_hierarchy[$pid]; break; }
        $n = strtolower($item->get_name());
        if (strpos($n, 'premium') !== false || strpos($n, 'custom') !== false) $new_level = 3;
        elseif (strpos($n, 'basic') !== false || strpos($n, 'standard') !== false) $new_level = 2;
        elseif (strpos($n, 'free') !== false || strpos($n, 'starter') !== false) $new_level = 1;
        if ($new_level > 0) break;
    }
 
    if ($new_level <= 1) { unset($running[$sub_id]); return; }
 
    // Addon TIDAK di-cancel — mereka tetap aktif dengan next_payment mereka sendiri
    unset($running[$sub_id]);
}
 
// ================================================================
// 20. Notice di halaman subscription jika addon di-cancel karena beda period
// ================================================================
add_action('woocommerce_subscription_details_after_subscription_table', 'show_addon_cancelled_notice', 5);
function show_addon_cancelled_notice($subscription) {
    $user_id = get_current_user_id();
    $flag    = get_user_meta($user_id, '_addon_cancelled_period_change', true);
    if (!$flag) return;
 
    foreach ($subscription->get_items() as $item) {
        $n = strtolower($item->get_name());
        if (strpos($n, 'basic') !== false || strpos($n, 'standard') !== false ||
            strpos($n, 'premium') !== false || strpos($n, 'custom') !== false ||
            strpos($n, 'byo') !== false) {
 
            echo '<div style="margin-bottom:15px;padding:15px;background:#fff3cd;border-left:4px solid #ffc107;border-radius:5px;">
                ⚠️ <strong>Your add-ons were cancelled</strong> because you switched to a different billing period.<br>
                <span style="font-size:13px;color:#666;">Please re-purchase your add-ons to continue using them under your new plan.</span><br>
                <a href="' . wc_get_account_endpoint_url('addons') . '"
                    style="display:inline-block;margin-top:8px;background:#3498db;color:#fff;padding:7px 15px;border-radius:5px;text-decoration:none;font-size:13px;font-weight:600;">
                    Browse Add-ons →
                </a>
            </div>';
 
            delete_user_meta($user_id, '_addon_cancelled_period_change');
            break;
        }
    }
}
 
// ================================================================
// 0B. Hide tombol Upgrade di Subscription Totals table
// ================================================================
add_filter('wcs_subscription_is_switchable_product', 'hide_switch_button_for_addon_products', 10, 2);
function hide_switch_button_for_addon_products($is_switchable, $product) {
    // Sembunyikan tombol Upgrade di product table — upgrade lewat Workspaces
    return false;
}
 
// ================================================================
// 0C. Hide Renew + Upgrade dari Actions row (semua hook yang relevan)
// ================================================================
 
// Hook utama WCS — hapus SEMUA action dari addon subscription
add_filter('wcs_view_subscription_actions', 'hide_addon_subscription_actions', 1, 2);
function hide_addon_subscription_actions($actions, $subscription) {
    if (!is_a($subscription, 'WC_Subscription')) return $actions;
 
    $is_addon = false;
    foreach ($subscription->get_items() as $item) {
        $pid = $item->get_product_id();
        if (has_term('add-on', 'product_cat', $pid)) {
            $is_addon = true;
            break;
        }
    }
 
    if (!$is_addon) return $actions;
 
    return []; // Addon — hapus semua action
}
 
// Block cancel untuk addon
add_filter('wcs_can_user_cancel_subscription', 'block_addon_cancel', 1, 2);
function block_addon_cancel($can_cancel, $subscription) {
    if (!is_a($subscription, 'WC_Subscription')) return $can_cancel;
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) {
            return false;
        }
    }
    return $can_cancel;
}
 
// Disable early renewal
add_filter('wcs_can_user_renew_early', 'disable_early_renewal_for_addon', 1, 2);
function disable_early_renewal_for_addon($can_renew, $subscription) {
    if (!is_a($subscription, 'WC_Subscription')) return $can_renew;
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) {
            return false;
        }
    }
    return $can_renew;
}
 
// Disable switch untuk addon
add_filter('wcs_can_user_switch_subscription', 'disable_switch_for_addon', 1, 3);
function disable_switch_for_addon($can_switch, $subscription, $product) {
    if (!is_a($subscription, 'WC_Subscription')) return $can_switch;
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) {
            return false;
        }
    }
    return $can_switch;
}
 
// ================================================================
// 0D. CSS + JS backstop — hapus paksa tombol Renew/Upgrade di halaman
//     addon subscription jika filter PHP tidak cukup
// ================================================================
add_action('wp_head', 'hide_addon_buttons_css_js');
function hide_addon_buttons_css_js() {
    if (!is_wc_endpoint_url('view-subscription')) return;
    if (!function_exists('wcs_get_subscription')) return;
 
    global $wp;
    $sub_id = isset($wp->query_vars['view-subscription']) ? (int)$wp->query_vars['view-subscription'] : 0;
    if (!$sub_id) return;
 
    $subscription = wcs_get_subscription($sub_id);
    if (!$subscription) return;
 
    ?>
    <style>
    /* Sembunyikan tombol Upgrade/switch/renew/auto-renew toggle di view-subscription */
    a[href*="switch-subscription"],
    a[href*="early-renewal"],
    a[href*="renew-subscription"],
    a[href*="toggle-auto-renew"],
    .subscription-auto-renew,
    .wcs-auto-renew-toggle,
    .subscription-auto-renew-toggle,
    .woocommerce-MyAccount-content .wcs-early-renewal-button,
    .woocommerce-MyAccount-content a.button.wcs-early-renewal-button {
        display: none !important;
    }
    /* Hide entire Auto renew row di subscription details table */
    .shop_table.subscription_details tr:has(.wcs-auto-renew-toggle),
    .shop_table.subscription_details tr:has(.subscription-auto-renew-toggle) {
        display: none !important;
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.woocommerce-orders-table__cell-actions a, .woocommerce-MyAccount-content .button, .woocommerce-MyAccount-content td a').forEach(function(btn) {
            var txt = btn.textContent.trim().toLowerCase();
            var href = btn.href || '';
            if (
                txt === 'upgrade' ||
                txt === 'disable auto-renew' ||
                txt === 'enable auto-renew' ||
                href.indexOf('switch-subscription') !== -1 ||
                href.indexOf('early-renewal') !== -1 ||
                href.indexOf('renew-subscription') !== -1 ||
                href.indexOf('toggle-auto-renew') !== -1
            ) {
                btn.parentNode && btn.parentNode.removeChild(btn);
            }
        });
        document.querySelectorAll('.wcs-early-renewal-modal, .wcs-early-renewal, .subscription-auto-renew').forEach(function(el) {
            el.style.display = 'none';
        });
    });
    </script>
    <?php
}
 
// ================================================================
// 0E. Hide tombol Resubscribe untuk cancelled plan
//     Jangan tampilkan resubscribe kalau user sudah punya plan
//     yang levelnya sama atau lebih tinggi
// ================================================================
add_filter('wcs_view_subscription_actions', 'hide_resubscribe_if_higher_plan_exists', 5, 2);
function hide_resubscribe_if_higher_plan_exists($actions, $subscription) {
    if (!isset($actions['resubscribe'])) return $actions;
    if (!function_exists('wcs_get_users_subscriptions')) return $actions;
 
    $plan_hierarchy  = phoenix_get_plan_hierarchy();
    $cancelled_level = 0;
 
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) return $actions;
        $cancelled_level = phoenix_get_plan_level_from_item($item, $plan_hierarchy);
        if ($cancelled_level > 0) break;
    }
    if ($cancelled_level === 0) return $actions;
 
    $user_id  = $subscription->get_user_id();
    $all_subs = wcs_get_users_subscriptions($user_id);
 
    foreach ($all_subs as $sub) {
        if ($sub->get_id() === $subscription->get_id()) continue;
        if (!$sub->has_status('active')) continue;
        foreach ($sub->get_items() as $item) {
            if (has_term('add-on', 'product_cat', $item->get_product_id())) continue;
            $level = phoenix_get_plan_level_from_item($item, $plan_hierarchy);
            if ($level >= $cancelled_level) {
                unset($actions['resubscribe']);
                return $actions;
            }
        }
    }
 
    return $actions;
}
 
// ================================================================
// 21. Upgrade Free→Basic atau Basic→Premium
//     Intercept SEBELUM plugin wbssaas_wcs_plan_payment_complete
//     untuk update tenant DB + Phoenix API tanpa createTenant baru
//
//     PERUBAHAN v2: Support KEDUA flow:
//     - Flow lama: WCS switch (_subscription_switch / _switched_subscription_id di order meta)
//     - Flow baru: add-to-cart (phoenix_upgrade_from_sub di WC session)
//     Fix: selalu pakai $tenant->tenant_location bukan global env setting
// ================================================================
add_action('woocommerce_subscription_payment_complete', 'phoenix_handle_plan_upgrade_tenant', 5, 1);
function phoenix_handle_plan_upgrade_tenant($subscription) {
    if (!function_exists('wcs_get_subscription')) return;
 
    $parent_order = wc_get_order($subscription->get_parent_id());
    if (!$parent_order) return;
 
    // ── Deteksi flow lama: WCS switch ────────────────────────────
    $switched_subscription_id = (int) $parent_order->get_meta('_subscription_switch');
    if (!$switched_subscription_id) {
        foreach ($parent_order->get_items() as $item) {
            $sid = (int) $item->get_meta('_switched_subscription_id');
            if ($sid) { $switched_subscription_id = $sid; break; }
        }
    }
 
    // ── Deteksi flow baru: add-to-cart dari upgrade-plan-page v3 ─
    $is_new_upgrade_flow = false;
    if (!$switched_subscription_id) {
        // Cek WC session dulu, fallback ke WP option (session tidak reliable dari AJAX)
        // Section 8 (status_updated) mungkin sudah clear session sebelum payment_complete ini fire
        $s21_user_id = $subscription->get_user_id();
        $from_sub_session = WC()->session ? (int) WC()->session->get('phoenix_upgrade_from_sub') : 0;
        $from_sub_option  = (int) get_option('_phoenix_upgrade_from_sub_' . $s21_user_id, 0);
        $from_sub = $from_sub_session ?: $from_sub_option;
        if ($from_sub > 0) {
            $switched_subscription_id = $from_sub;
            $is_new_upgrade_flow      = true;
            // Clear session dan WP option setelah dipakai di section 21
            if (WC()->session) {
                WC()->session->set('phoenix_upgrade_from_sub', null);
                WC()->session->set('phoenix_upgrade_item_id',  null);
            }
            delete_option('_phoenix_upgrade_from_sub_' . $s21_user_id);
            delete_option('_phoenix_upgrade_org_'      . $s21_user_id);
        }
    }
 
    if (!$switched_subscription_id) return; // Bukan upgrade — biarkan plugin handle normal
 
    // Pastikan ini main plan, bukan addon
    $new_plan_level = 0;
    $plan_hierarchy = [30688 => 1, 11 => 1, 58 => 2, 22 => 2, 76 => 3, 33 => 3];
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) return;
        $pid = $item->get_product_id();
        $level = isset($plan_hierarchy[$pid]) ? $plan_hierarchy[$pid] : 0;
        if ($level === 0) {
            $n = strtolower($item->get_name());
            if (strpos($n, 'premium') !== false || strpos($n, 'custom') !== false) $level = 3;
            elseif (strpos($n, 'basic') !== false || strpos($n, 'standard') !== false) $level = 2;
            elseif (strpos($n, 'free') !== false || strpos($n, 'starter') !== false) $level = 1;
        }
        if ($level > 0) { $new_plan_level = $level; break; }
    }
    if ($new_plan_level <= 1) return;
 
    // Ambil tenant dari subscription LAMA
    global $wpdb;
    $table  = $wpdb->prefix . 'wbssaas_tenants';
    $tenant = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table WHERE subscription_wc_id = %d LIMIT 1",
        (int) $switched_subscription_id
    ));
 
    if (!$tenant) {
        error_log('phoenix_handle_plan_upgrade_tenant: no tenant for old sub #' . $switched_subscription_id);
        return;
    }
 
    // Ambil default_package dari plugin config
    $wc_config_path = WP_PLUGIN_DIR . '/wbs-saas-wp/config/wc.php';
    if (!file_exists($wc_config_path)) return;
    $wc_config  = include $wc_config_path;
    $plan_key   = $new_plan_level === 3 ? 'premium' : 'basic';
    $new_package = isset($wc_config['subscription'][$plan_key]['default_package'])
        ? $wc_config['subscription'][$plan_key]['default_package']
        : null;
    if (!$new_package) return;

    // ── Merge addon quantities from old plan into new plan defaults ──────────
    // Why: tenant_settings di DB menyimpan base + semua addon yang sudah dibeli.
    // Saat upgrade, new_package dari config hanya berisi default (base) plan baru.
    // Kita perlu carry-over semua addon yang sudah dibeli user ke settings baru,
    // menggunakan MAX logic supaya addon tidak hilang setelah upgrade.
    $old_settings = @unserialize($tenant->tenant_settings);
    if (is_array($old_settings)) {
        foreach (['phone', 'email', 'im', 'postmail', 'chat', 'mobileapp', 'languages'] as $_ch) {
            $old_val = (int)($old_settings[$_ch] ?? 0);
            $new_val = (int)($new_package[$_ch] ?? 0);
            if ($old_val > $new_val) $new_package[$_ch] = $old_val;
        }
        foreach (['manager', 'operator', 'agent'] as $_role) {
            $old_val = (int)($old_settings['users'][$_role] ?? 0);
            $new_val = (int)($new_package['users'][$_role] ?? 0);
            if ($old_val > $new_val) $new_package['users'][$_role] = $old_val;
        }
        if (!empty($old_settings['themes']) && is_array($old_settings['themes'])) {
            $existing = $new_package['themes'] ?? [];
            $new_package['themes'] = array_values(array_unique(array_merge($existing, $old_settings['themes'])));
        }
    }

    // Step 1: Update tenant DB
    // FIX: juga update tenant_url ke subdomain asli dari WP option
    // tenant_url sebelumnya berisi upgrade-xxx (fake) dari GF entry saat checkout
    $db_update_data = [
        'subscription_wc_id' => $subscription->get_id(),
        'tenant_settings'    => serialize($new_package),
        'modified'           => current_time('mysql'),
    ];
 
    // Ambil subdomain & domain asli dari WP option (disimpan saat AJAX di upgrade-plan-page)
    if (!isset($s21_user_id)) $s21_user_id = $subscription->get_user_id();
    $real_subdomain = get_option('_phoenix_upgrade_subdomain_' . $s21_user_id);
    $real_domain    = get_option('_phoenix_upgrade_domain_'    . $s21_user_id);
    $options_env    = get_option('wbssaas_options');
    $is_staging_env = isset($options_env['environment']) && $options_env['environment'] == 'staging';
 
    if ($real_subdomain && $real_domain) {
        // Bangun tenant_url yang benar (dengan .stg. kalau staging)
        $stg_prefix = $is_staging_env ? '.stg.' : '.';
        $real_tenant_url = $real_subdomain . $stg_prefix . $real_domain;
        if (strpos($tenant->tenant_url, 'upgrade-') === 0 || strpos($tenant->tenant_url ?? '', 'upgrade-') !== false) {
            $db_update_data['tenant_url'] = $real_tenant_url;
        }
    }
 
    $result = $wpdb->update(
        $table,
        $db_update_data,
        ['id' => $tenant->id]
    );
 
 
    if ($result === false) {
        error_log('phoenix_handle_plan_upgrade_tenant: DB update failed for tenant #' . $tenant->id);
        return;
    }
 
    // Step 2: Kirim update ke Phoenix API
    // FIX: selalu pakai $tenant->tenant_location, bukan global environment setting
    $location = $tenant->tenant_location;
 
    $tenant->subscription_wc_id = $subscription->get_id();
 
    // Step 3: Block plugin SEBELUM API call — jangan tunggu API success
    // Kalau di-block setelah API call, ada window dimana plugin bisa run duluan
    remove_action('woocommerce_subscription_payment_complete', 'wbssaas_wcs_plan_payment_complete', 11);
    remove_action('woocommerce_subscription_payment_complete', 'wbssaas_wcs_addon_payment_complete', 11);
    $parent_order->update_meta_data('_wbssaas_upgrade_handled', '1');
    $parent_order->save();
 
    // Step 4: Update subscription_expired di DB supaya row tidak tampil crossed-out di admin
    $new_next_payment = $subscription->get_date('next_payment', 'site');
    if ($new_next_payment) {
        $wpdb->update($table, ['subscription_expired' => $new_next_payment], ['id' => $tenant->id]);
    }
 
    try {
        $api      = new \WBSSaaS\PhoenixAPI($location);
        $response = $api->updatePackage($tenant, $new_package);
 
        if (!$response || !isset($response->data)) {
            error_log('phoenix_handle_plan_upgrade_tenant: API updatePackage failed for tenant ' . $tenant->tenant_uuid);
            $subscription->add_order_note('Plan upgrade DB updated but API updatePackage failed for tenant ' . $tenant->tenant_uuid . '. Manual sync may be needed.');
        } else {
            $subscription->add_order_note(sprintf(
                'Plan upgraded to %s via %s flow. Tenant DB + Phoenix API updated.',
                ucfirst($plan_key),
                $is_new_upgrade_flow ? 'add-to-cart' : 'WCS switch'
            ));
        }
    } catch (Exception $e) {
        error_log('phoenix_handle_plan_upgrade_tenant: Exception — ' . $e->getMessage());
    }
 
    // ── Section 21B: Detect period change monthly→yearly, flag addons for conversion ──
    // Jika upgrade mengubah billing period dari monthly ke yearly,
    // semua addon monthly instance ini perlu dikonversi ke yearly pada renewal berikutnya.
    // Caranya: set meta _phoenix_switch_to_yearly pada masing-masing addon subscription.
    // Konversi actual dilakukan di Section 22 saat renewal date tiba.
    // NOTE: Ini di dalam function — variabel $switched_subscription_id, $tenant,
    //       $subscription semua accessible dari scope di atas.
    $old_sub_chk = wcs_get_subscription($switched_subscription_id);
    if ($old_sub_chk && !isset($s21_period_checked)) {
        $s21_period_checked = true;
        $old_bp = $old_sub_chk->get_billing_period();
        $new_bp = $subscription->get_billing_period();
        $old_yearly_chk = ($old_bp === 'year') || ($old_bp === 'month' && (int)$old_sub_chk->get_billing_interval() >= 12);
        $new_yearly_chk = ($new_bp === 'year') || ($new_bp === 'month' && (int)$subscription->get_billing_interval() >= 12);
 
        if (!$old_yearly_chk && $new_yearly_chk && isset($tenant)) {
            // Period berubah monthly → yearly
            // Cari semua addon monthly yang belong ke instance ini
            $s21_user_id_x = $subscription->get_user_id();
            $s21_all_subs  = function_exists('wcs_get_users_subscriptions')
                ? wcs_get_users_subscriptions($s21_user_id_x) : [];
            $s21_uuid      = $tenant->tenant_uuid ?? '';
            $s21_flagged   = 0;
 
            foreach ($s21_all_subs as $_addon_sub) {
                if (!$_addon_sub->has_status('active')) continue;
 
                // Harus addon
                $_is_addon = false;
                foreach ($_addon_sub->get_items() as $_ait) {
                    if (has_term('add-on', 'product_cat', $_ait->get_product_id())) {
                        $_is_addon = true; break;
                    }
                }
                if (!$_is_addon) continue;
 
                // Harus monthly (yang perlu dikonversi)
                $_addon_period = $_addon_sub->get_billing_period();
                if ($_addon_period === 'year') continue; // already yearly, skip
 
                // Harus milik instance ini — UUID matching (3 methods)
                $_belongs = false;
                $_oid = $_addon_sub->get_parent_id();
 
                if (!$_belongs && $s21_uuid && function_exists('GFAPI') && $_oid) {
                    $_ents = GFAPI::get_entries(64, ['field_filters' => [
                        ['key' => 'woocommerce_order_number', 'value' => $_oid]
                    ]]);
                    if (!empty($_ents) && ($s21_uuid === ($_ents[0]['1'] ?? ''))) $_belongs = true;
                }
                if (!$_belongs && $s21_uuid && $_oid) {
                    $_ord = wc_get_order($_oid);
                    if ($_ord) {
                        foreach ($_ord->get_items() as $_oi) {
                            foreach ($_oi->get_meta_data() as $_om) {
                                if ((string)$_om->value === $s21_uuid) { $_belongs = true; break 2; }
                            }
                        }
                    }
                }
                if (!$_belongs && $s21_uuid && $_oid) {
                    global $wpdb;
                    $_gfv = $wpdb->get_var($wpdb->prepare(
                        "SELECT em.meta_value FROM {$wpdb->prefix}gf_entry_meta em
                         INNER JOIN {$wpdb->prefix}gf_entry e ON e.id = em.entry_id
                         WHERE e.id IN (
                             SELECT entry_id FROM {$wpdb->prefix}gf_entry_meta
                             WHERE meta_key = 'woocommerce_order_number' AND meta_value = %s
                         ) AND em.meta_key = '1' AND e.form_id = 64 LIMIT 1",
                        (string)$_oid
                    ));
                    if ($_gfv === $s21_uuid) $_belongs = true;
                }
                if (!$_belongs) continue;
 
                // Find yearly variant of this addon product
                $_yearly_var_id = 0;
                $_yearly_price  = 0;
                foreach ($_addon_sub->get_items() as $_ait2) {
                    $_parent_pid = wc_get_product($_ait2->get_product_id());
                    $_parent_pid = $_parent_pid ? ($_parent_pid->get_parent_id() ?: $_ait2->get_product_id()) : $_ait2->get_product_id();
                    $_parent_prod = wc_get_product($_parent_pid);
                    if (!$_parent_prod) continue;
                    foreach ($_parent_prod->get_children() as $_child_id) {
                        $_child = wc_get_product($_child_id);
                        if (!$_child || !$_child->is_purchasable()) continue;
                        if (get_post_meta($_child_id, '_subscription_period', true) === 'year') {
                            $_yearly_var_id = $_child_id;
                            $_yearly_price  = (float) $_child->get_price();
                            break;
                        }
                    }
                    if ($_yearly_var_id) break;
                }
 
                if (!$_yearly_var_id) {
                    // No yearly variant found — just notify, don't convert
                    $_addon_sub->add_order_note('Period mismatch: plan upgraded to yearly but no yearly variant found for this addon.');
                    continue;
                }
 
                // Flag addon for conversion at next renewal
                $_addon_sub->update_meta_data('_phoenix_switch_to_yearly', $_yearly_var_id);
                $_addon_sub->update_meta_data('_phoenix_switch_to_yearly_price', $_yearly_price);
                $_addon_sub->update_meta_data('_phoenix_switch_conversion_date', $subscription->get_date('next_payment'));
                $_addon_sub->save();
                $_addon_sub->add_order_note(sprintf(
                    'Flagged for period conversion: monthly → yearly at next renewal (%s). Yearly variant ID: %d, price: %s.',
                    $subscription->get_date('next_payment'),
                    $_yearly_var_id,
                    wc_price($_yearly_price)
                ));
                $s21_flagged++;
            }
 
            if ($s21_flagged > 0) {
                $subscription->add_order_note(sprintf(
                    '%d addon subscription(s) flagged for monthly→yearly conversion at next renewal.',
                    $s21_flagged
                ));
            }
        }
    }
} // END phoenix_handle_plan_upgrade_tenant
 
 
// NOTE: phoenix_skip_plugin_create_on_upgrade dihapus.
// Plugin hook (wbssaas_wcs_plan_payment_complete) sudah di-remove
// langsung di dalam phoenix_handle_plan_upgrade_tenant (Section 21, priority 5)
// via remove_action sebelum API call — lebih solid, tidak ada race condition.
 
// ================================================================
// 0F. Renew button logic — H-30 sebelum next_payment di semua instance
// Rules:
// - Free plan         → always hidden
// - Addon             → always hidden (ikut main plan)
// - Monthly/Yearly    → tampil H-30 sebelum next_payment subscription itu
// - Per subscription  → cek masing-masing, bukan global per user
// ================================================================
function phoenix_should_show_renew($subscription) {
    if (!is_a($subscription, 'WC_Subscription')) return false;
    if (!$subscription->has_status('active')) return false;
 
    // Addon → always hidden
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) return false;
    }
 
    // Free plan → always hidden
    $plan_level = function_exists('phoenix_get_subscription_plan_level')
        ? phoenix_get_subscription_plan_level($subscription) : 0;
    if ($plan_level <= 1) return false;
 
    $next_payment = $subscription->get_date('next_payment');
    if (!$next_payment) return false;
 
    $days_to_next = (int) floor((strtotime($next_payment) - time()) / DAY_IN_SECONDS);
 
    return $days_to_next <= 30 && $days_to_next >= 0;
}
 
add_filter('wcs_view_subscription_actions', 'phoenix_control_renew_button', 10, 2);
function phoenix_control_renew_button($actions, $subscription) {
    // Renew now selalu hidden — auto-renewal aktif, manual renew tidak diperlukan
    unset($actions['renew']);
    unset($actions['early-renewal']);
    unset($actions['subscription_renewal_early']);
    foreach ($actions as $key => $action) {
        if (isset($action['url']) && (
            strpos($action['url'], 'early-renewal') !== false ||
            strpos($action['url'], 'renew-subscription') !== false ||
            strpos($action['url'], 'subscription_renewal_early') !== false ||
            strpos($action['url'], 'subscription_renewal=true') !== false
        )) {
            unset($actions[$key]);
        }
    }
    return $actions;
}

// CSS backstop — force hide renew button via CSS+JS when filter not enough
add_action('wp_head', 'phoenix_hide_renew_css_backstop');
function phoenix_hide_renew_css_backstop() {
    if (!is_wc_endpoint_url('view-subscription')) return;
    if (!function_exists('wcs_get_subscription')) return;
 
    global $wp;
    $sub_id = isset($wp->query_vars['view-subscription']) ? (int)$wp->query_vars['view-subscription'] : 0;
    if (!$sub_id) return;
 
    $subscription = wcs_get_subscription($sub_id);
    if (!$subscription || !$subscription->has_status('active')) return;
 
    ?>
    <style>
    a[href*="early-renewal"],
    a[href*="renew-subscription"],
    a[href*="subscription_renewal_early"],
    a[href*="subscription_renewal=true"],
    .wcs-early-renewal-button,
    .subscription_renewal_early,
    [id^="wcs-early-renewal-modal"],
    .wcs-modal[id*="early-renewal"] { display: none !important; }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a, button').forEach(function(el) {
            var h = el.href || '';
            var t = (el.textContent || '').trim().toLowerCase();
            if (h.indexOf('early-renewal') !== -1 || h.indexOf('renew-subscription') !== -1 ||
                h.indexOf('subscription_renewal_early') !== -1 || h.indexOf('subscription_renewal=true') !== -1 ||
                t === 'renew now' || t === 'renew') {
                el.style.display = 'none';
            }
        });
        document.querySelectorAll('[id^="wcs-early-renewal-modal"], .wcs-modal').forEach(function(el) {
            if (el.id && el.id.indexOf('early-renewal') !== -1) el.style.display = 'none';
        });
    });
    </script>
    <?php
}
 
// ================================================================
// SECTION 22: INTERCEPT ADDON RENEWAL — CONVERT MONTHLY TO YEARLY
//
// Fires on WP-Cron scheduled payment BEFORE payment is processed.
// Jika addon punya flag _phoenix_switch_to_yearly:
//   1. Update line item ke yearly variant + yearly price
//   2. Update billing_period = 'year', billing_interval = 1
//   3. Update next_payment = +1 year
//   4. Clear flag
//   5. WCS lanjut proses renewal dengan amount baru (yearly price)
//
// Result: Renewal pertama setelah upgrade = charge yearly price.
// Tidak ada charge bulanan yang lolos.
// ================================================================
add_action('woocommerce_scheduled_subscription_payment', 'phoenix_convert_addon_to_yearly_on_renewal', 1, 1);
function phoenix_convert_addon_to_yearly_on_renewal($subscription_id) {
    if (!function_exists('wcs_get_subscription')) return;
 
    $sub = wcs_get_subscription($subscription_id);
    if (!$sub || !$sub->has_status('active')) return;
 
    // Cek apakah addon ini punya flag konversi
    $yearly_var_id = (int) $sub->get_meta('_phoenix_switch_to_yearly');
    if (!$yearly_var_id) return;
 
    $yearly_price = (float) $sub->get_meta('_phoenix_switch_to_yearly_price');
 
    // Pastikan ini addon subscription
    $_is_addon = false;
    foreach ($sub->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) {
            $_is_addon = true; break;
        }
    }
    if (!$_is_addon) return;
 
    // Guard: jangan jalankan kalau sudah yearly (double-check)
    if ($sub->get_billing_period() === 'year') {
        $sub->delete_meta_data('_phoenix_switch_to_yearly');
        $sub->delete_meta_data('_phoenix_switch_to_yearly_price');
        $sub->delete_meta_data('_phoenix_switch_conversion_date');
        $sub->save();
        return;
    }
 
    $yearly_product = wc_get_product($yearly_var_id);
    if (!$yearly_product) {
        error_log('phoenix_convert_addon_to_yearly: yearly product not found, var_id=' . $yearly_var_id);
        return;
    }
 
    // Validasi harga yearly
    if ($yearly_price <= 0) {
        $yearly_price = (float) $yearly_product->get_price();
    }
    if ($yearly_price <= 0) {
        error_log('phoenix_convert_addon_to_yearly: yearly price is 0, aborting conversion sub #' . $subscription_id);
        return;
    }
 
    try {
        // ── 1. Update line items ke yearly product ────────────────
        foreach ($sub->get_items() as $item) {
            if (!has_term('add-on', 'product_cat', $item->get_product_id())) continue;
 
            $qty = max(1, (int) $item->get_quantity());
 
            // Update product reference ke yearly variant
            $item->set_product_id($yearly_product->get_parent_id() ?: $yearly_var_id);
            $item->set_variation_id($yearly_var_id);
            $item->set_name($yearly_product->get_name());
 
            // Update price ke yearly price
            $item->set_subtotal($yearly_price * $qty);
            $item->set_total($yearly_price * $qty);
            $item->set_subtotal_tax(0);
            $item->set_total_tax(0);
            $item->save();
        }
 
        // ── 2. Update billing schedule ke yearly ─────────────────
        // WCS stores billing_period, billing_interval as post meta
        $sub->set_billing_period('year');
        $sub->set_billing_interval(1);
 
        // ── 3. Recalculate totals ─────────────────────────────────
        $sub->set_total($yearly_price);
        $sub->calculate_totals();
 
        // ── 4. Update next_payment = +1 year from today ──────────
        $now             = current_time('timestamp');
        $new_next_payment = date('Y-m-d H:i:s', strtotime('+1 year', $now));
        $sub->update_dates(['next_payment' => $new_next_payment]);
 
        // ── 5. Clear conversion flag ──────────────────────────────
        $sub->delete_meta_data('_phoenix_switch_to_yearly');
        $sub->delete_meta_data('_phoenix_switch_to_yearly_price');
        $sub->delete_meta_data('_phoenix_switch_conversion_date');
 
        // ── 6. Save all changes ───────────────────────────────────
        $sub->save();
 
        $sub->add_order_note(sprintf(
            '✅ Addon converted from Monthly to Yearly billing. Yearly price: %s. Next renewal: %s.',
            wc_price($yearly_price),
            date('d M Y', strtotime($new_next_payment))
        ));
 
        error_log('phoenix_convert_addon_to_yearly: SUCCESS sub #' . $subscription_id
            . ' var_id=' . $yearly_var_id . ' price=' . $yearly_price);
 
    } catch (Exception $e) {
        error_log('phoenix_convert_addon_to_yearly: ERROR sub #' . $subscription_id . ' — ' . $e->getMessage());
    }
}
 
// ================================================================
// SECTION 22B: DISPLAY PENDING CONVERSION STATUS
// Helper function untuk menu-addon.php — cek apakah addon sedang
// menunggu konversi ke yearly
// ================================================================
if (!function_exists('phoenix_addon_pending_yearly_conversion')) {
    function phoenix_addon_pending_yearly_conversion($addon_sub) {
        if (!is_a($addon_sub, 'WC_Subscription')) return false;
        return (bool) $addon_sub->get_meta('_phoenix_switch_to_yearly');
    }
}
 
if (!function_exists('phoenix_addon_pending_conversion_date')) {
    function phoenix_addon_pending_conversion_date($addon_sub) {
        if (!is_a($addon_sub, 'WC_Subscription')) return '';
        $date = $addon_sub->get_meta('_phoenix_switch_conversion_date');
        return $date ? date('d M Y', strtotime($date)) : '';
    }
}
 
if (!function_exists('phoenix_addon_pending_yearly_price')) {
    function phoenix_addon_pending_yearly_price($addon_sub) {
        if (!is_a($addon_sub, 'WC_Subscription')) return 0;
        return (float) $addon_sub->get_meta('_phoenix_switch_to_yearly_price');
    }
}