/**
 * ================================================================
 * SNIPPET: ADDON PRORATE + SYNC
 * Versi: v10 - Tambah Section 10 & 11: Prorate Upgrade Main Plan
 *
 * v9 changes dari v8:
 * + NEW Section 10: prorate saat upgrade main plan (Basic→Premium)
 *   Semua skenario: Monthly→Monthly, Monthly→Yearly, Yearly→Yearly
 *   Credit = old_plan_price × (days_remaining / period_days)
 *   Pakai helper phoenix_prorate_get_period_days() — consistent dengan addon prorate
 *   Deteksi upgrade via session phoenix_upgrade_from_sub (di-set oleh upgrade-plan flow)
 * + NEW Section 11: set full price di new plan subscription line item setelah upgrade
 *   Tanpa ini: WCS simpan harga post-discount → semua renewal ke depan ter-prorate ❌
 *   Dengan ini: first payment = prorated, semua renewal = full price ✅
 * + NEW Helper phoenix_get_old_plan_slug(): ambil slug plan dari subscription items
 *   Wrapped dengan function_exists() guard agar tidak conflict
 *
 * v8 changes (tetap berlaku):
 * + BUG FIX: phoenix_prorate_get_period_days() — subscription baru (renewal=0)
 *   pakai strtotime('-1 month', next_payment) → period_days ~30 hari (benar)
 *
 * Sections lengkap:
 *   1  - Prorate addon first payment (cart fee)
 *   2  - Sync next_payment addon saat dibuat
 *   4  - Badge "Save X%" di cart item
 *   5  - Sync next_payment addon setelah main plan renewal
 *   6  - Set full price di addon subscription line item
 *   7  - Retroactive sync saat admin update next_payment (loop guard)
 *   8  - Tag order note di renewal order
 *   9  - Tag order note di first payment (prorated)
 *   10 - Prorate upgrade main plan (credit dari old plan)        ← NEW v9
 *   11 - Set full price di new plan subscription line item       ← NEW v9
 * ================================================================
 */

// ================================================================
// HELPER: Ambil main plan subscription dari tenant_uuid
// ================================================================
if (!function_exists('phoenix_get_main_sub_from_uuid')) {
    function phoenix_get_main_sub_from_uuid($tenant_uuid, $user_id = 0) {
        global $wpdb;
        if (!$tenant_uuid) return null;

        $uid = $user_id ?: get_current_user_id();
        $row = $wpdb->get_row($wpdb->prepare(
            "SELECT subscription_wc_id FROM {$wpdb->prefix}wbssaas_tenants
             WHERE tenant_uuid = %s AND customer_id = %d
             ORDER BY created ASC LIMIT 1",
            $tenant_uuid, $uid
        ));

        if (!$row || !$row->subscription_wc_id) return null;
        if (!function_exists('wcs_get_subscription')) return null;

        $sub = wcs_get_subscription((int) $row->subscription_wc_id);
        return ($sub && $sub->has_status('active')) ? $sub : null;
    }
}

// ================================================================
// HELPER INTERNAL: Hitung period_days secara akurat dari actual dates
// Menggunakan last_payment → next_payment (bukan date('t') yang pakai bulan saat ini)
//
// Kenapa lebih akurat:
// date('t') di bulan April = 30, tapi kalau periode adalah Maret→April = 31 hari
// Dengan last_payment→next_payment kita dapat angka actual, bukan asumsi
// ================================================================
if (!function_exists('phoenix_prorate_get_period_days')) {
function phoenix_prorate_get_period_days($main_sub) {
    $billing_period   = $main_sub->get_billing_period();
    $billing_interval = max(1, (int) $main_sub->get_billing_interval());
    $next_payment     = $main_sub->get_date('next_payment');
    $np_ts            = strtotime($next_payment);

    if ($billing_period === 'month') {
        // Cek apakah ini subscription baru (belum pernah renewal)
        // Kalau renewal_count = 0, last_payment = tanggal buat subscription (bukan periode sebelumnya)
        // Contoh bug: buat 14 Apr, next_payment diset ke 30 Apr (15 hari) →
        //   last→next = 16 hari, bukan 30 hari → period_days salah → grace check skip prorate
        $renewals = $main_sub->get_related_orders('ids', 'renewal');
        $is_new   = (count($renewals) === 0);

        if (!$is_new) {
            // Subscription sudah pernah renewal: pakai last_payment → next_payment (akurat)
            $last_payment = $main_sub->get_date('last_payment');
            if ($last_payment) {
                $lp_ts       = strtotime($last_payment);
                $period_days = max(1, (int) round(($np_ts - $lp_ts) / DAY_IN_SECONDS));
            } else {
                $prev_np_ts  = strtotime('-' . $billing_interval . ' month', $np_ts);
                $period_days = max(1, (int) round(($np_ts - $prev_np_ts) / DAY_IN_SECONDS));
            }
        } else {
            // Subscription baru (belum pernah renewal): hitung dari billing period sebenarnya
            // Mundur $billing_interval bulan dari next_payment → dapat "seharusnya kapan periode mulai"
            // Ini menghasilkan ~30 hari untuk monthly, bukan jarak last_payment → next_payment
            $prev_np_ts  = strtotime('-' . $billing_interval . ' month', $np_ts);
            $period_days = max(1, (int) round(($np_ts - $prev_np_ts) / DAY_IN_SECONDS));
        }
    } elseif ($billing_period === 'year') {
        $period_days = 365 * $billing_interval;
    } else {
        $period_days = 30; // Unknown period — default safe
    }

    return max(1, $period_days);
}
}

// ================================================================
// SECTION 0: BSW CART GATE
// Hook: woocommerce_add_to_cart_validation
//
// Blokir pembelian addon (dan theme) kalau user belum selesaikan
// Basic Setup Wizard untuk instance yang bersangkutan.
//
// Flow:
// 1. Cek apakah produk yang ditambahkan adalah addon/theme
// 2. Ambil tenant_uuid dari URL atau session
// 3. Cari main plan subscription dari tenant_uuid
// 4. Cek phoenix_is_wizard_complete()
// 5. Kalau belum complete → block + notice
//
// Fail open: kalau phoenix_is_wizard_complete tidak ada / API error → allow
// ================================================================
add_filter('woocommerce_add_to_cart_validation', 'phoenix_bsw_cart_gate', 10, 2);
function phoenix_bsw_cart_gate($passed, $product_id) {
    if (!$passed) return false;
    if (!is_user_logged_in()) return $passed;

    // Hanya berlaku untuk addon dan theme
    $is_addon = has_term('add-on', 'product_cat', $product_id);
    $is_theme = has_term('theme', 'product_cat', $product_id);
    if (!$is_addon && !$is_theme) return $passed;

    // Ambil tenant_uuid dari URL atau session
    $tenant_uuid = '';
    if (!empty($_GET['tenant_uuid'])) {
        $tenant_uuid = sanitize_text_field($_GET['tenant_uuid']);
    } elseif (WC()->session) {
        $tenant_uuid = WC()->session->get('addon_tenant_uuid', '');
    }
    if (!$tenant_uuid) return $passed;

    // Cari main plan subscription dari tenant_uuid
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare(
        "SELECT subscription_wc_id FROM {$wpdb->prefix}wbssaas_tenants
         WHERE tenant_uuid = %s AND customer_id = %d
         ORDER BY created ASC LIMIT 1",
        $tenant_uuid, get_current_user_id()
    ));
    if (!$row || !$row->subscription_wc_id) return $passed;

    // Fail open: kalau helper tidak ada, allow
    if (!function_exists('phoenix_is_wizard_complete')) return $passed;

    if (!phoenix_is_wizard_complete((int) $row->subscription_wc_id, get_current_user_id())) {
        wc_add_notice(
            'Please complete the Basic Setup Wizard for your instance before purchasing add-ons or themes.',
            'error'
        );
        return false;
    }

    return $passed;
}

// ================================================================
// SECTION 1: PRORATE FIRST PAYMENT
// Hook: woocommerce_cart_calculate_fees
//
// Kondisi aktif:
// - Cart berisi produk add-on category
// - Session 'addon_tenant_uuid' ada (di-set dari product page via ?tenant_uuid=)
// - Main plan next_payment belum lewat
// - Sisa hari < full billing period - 2 (ada potongan yang meaningful)
//
// Grace period = 2 hari:
// Beli hari 1-2 setelah renewal → skip prorate (prorate amount < 7% — tidak berarti)
// Beli hari 3+ → prorate aktif
// ================================================================
add_action('woocommerce_cart_calculate_fees', 'phoenix_addon_prorate_first_payment');
function phoenix_addon_prorate_first_payment($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;
    if (!WC()->session) return;

    $tenant_uuid = WC()->session->get('addon_tenant_uuid', '');
    if (!$tenant_uuid) return;

    // PENTING: Skip kalau ini recurring cart calculation (bukan initial payment)
    // WC Subscriptions set recurring_cart_key saat hitung renewal amount
    if (!empty($cart->recurring_cart_key)) return;
    if (function_exists('wcs_is_recurring_cart') && wcs_is_recurring_cart($cart)) return;

    // Cek apakah cart berisi addon
    $has_addon = false;
    foreach ($cart->get_cart() as $item) {
        if (has_term('add-on', 'product_cat', $item['product_id'])) {
            $has_addon = true;
            break;
        }
    }
    if (!$has_addon) return;

    // Ambil main plan dari tenant_uuid
    $main_sub = phoenix_get_main_sub_from_uuid($tenant_uuid);
    if (!$main_sub) return;

    $next_payment = $main_sub->get_date('next_payment');
    if (!$next_payment) return;

    $now   = current_time('timestamp');
    $np_ts = strtotime($next_payment);

    // Kalau next_payment sudah lewat → tidak perlu prorate
    if ($np_ts <= $now) return;

    // Hitung period_days secara akurat dari actual last_payment → next_payment
    $period_days    = phoenix_prorate_get_period_days($main_sub);
   	$days_remaining = (int) floor(($np_ts - $now) / DAY_IN_SECONDS);

    // Grace period: skip kalau sisa >= period - 1 hari
    // -2 (bukan -1) untuk handle:
    // 1. Timezone offset yang bisa bikin ceil() tambah 1 hari
    // 2. Pembelian di hari pertama atau kedua setelah renewal
    // 3. Cegah prorate kecil (<6%) yang membingungkan user
    if ($days_remaining >= ($period_days - 1)) return;

    $prorate_ratio  = $days_remaining / $period_days;
    $total_discount = 0;

    // Ambil currency aktif (WCML)
    $currency = get_woocommerce_currency();
    if (function_exists('wcml_get_woocommerce_currency_option')) {
        $currency = apply_filters('wcml_price_currency', $currency);
    }

    foreach ($cart->get_cart() as $item) {
        if (!has_term('add-on', 'product_cat', $item['product_id'])) continue;

        // Ambil harga full price dalam currency aktif via WCML
        $product    = wc_get_product($item['product_id']);
        $base_price = $product ? (float) $product->get_regular_price() : 0;
        $full_price = $base_price;
        if ($base_price > 0) {
            $full_price = (float) apply_filters('wcml_raw_price_amount', $base_price, $currency);
        }

        // Fallback ke line_total kalau WCML tidak return harga
        $line_total      = ($full_price > 0) ? $full_price * $item['quantity'] : (float) $item['line_total'];
        $total_discount += $line_total - ($line_total * $prorate_ratio);
    }

    if ($total_discount <= 0) return;

    $cart->add_fee(
        phoenix_text('prorate.cart_fee_label', $days_remaining),
        -round($total_discount, 2),
        false // false = tidak kena tax
    );

    // Simpan next_payment ke session untuk dipakai di Section 2
    WC()->session->set('addon_prorate_np', $next_payment);
}

// ================================================================
// SECTION 2: SYNC NEXT_PAYMENT SAAT ADDON SUBSCRIPTION DIBUAT
// Hook: woocommerce_checkout_subscription_created (priority 10)
//
// Set next_payment addon = next_payment main plan
// sehingga renewal pertama sejajar — bukan +1 bulan dari sekarang
// ================================================================
add_action('woocommerce_checkout_subscription_created', 'phoenix_sync_addon_np_on_create', 10, 3);
function phoenix_sync_addon_np_on_create($subscription, $order, $recurring_cart) {
    // Cek apakah ini addon subscription
    $is_addon = false;
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) {
            $is_addon = true;
            break;
        }
    }
    if (!$is_addon) return;

    // Ambil next_payment dari session (di-set di Section 1)
    $next_payment = WC()->session ? WC()->session->get('addon_prorate_np', '') : '';

    // Fallback: cari langsung dari tenant_uuid kalau session tidak ada
    // (misal: user beli addon di hari pertama periode, prorate di-skip tapi sync tetap perlu)
    if (!$next_payment) {
        $tenant_uuid = WC()->session ? WC()->session->get('addon_tenant_uuid', '') : '';
        $main_sub    = phoenix_get_main_sub_from_uuid($tenant_uuid, $order->get_user_id());
        if ($main_sub) $next_payment = $main_sub->get_date('next_payment');
    }

    if (!$next_payment) return;

    // Jangan sync kalau next_payment sudah lewat
    if (strtotime($next_payment) <= current_time('timestamp')) return;

    try {
        $subscription->update_dates(['next_payment' => $next_payment]);
        $subscription->save();
        $subscription->add_order_note(
            phoenix_text('prorate.note_addon_np_synced', $next_payment)
        );
    } catch (Exception $e) {
        error_log('Addon next_payment sync error (sub #' . $subscription->get_id() . '): ' . $e->getMessage());
    }

    // Clear session prorate_np — sudah dipakai
    if (WC()->session) {
        WC()->session->set('addon_prorate_np', null);
    }
}

// ================================================================
// SECTION 3: (removed)
// Digantikan oleh Section 7 yang punya loop guard.
// ================================================================

// ================================================================
// SECTION 4: BADGE "Save X%" DI CART ITEM NAME
// Hook: woocommerce_cart_item_name
//
// Tampilkan badge dinamis berapa persen hemat di baris addon
// Konsisten dengan Section 1: pakai period_days helper + grace -2
// ================================================================
add_filter('woocommerce_cart_item_name', 'phoenix_addon_prorate_badge', 10, 3);
function phoenix_addon_prorate_badge($name, $cart_item, $cart_item_key) {
    if (is_admin()) return $name;
    if (!WC()->session) return $name;

    $tenant_uuid = WC()->session->get('addon_tenant_uuid', '');
    if (!$tenant_uuid) return $name;

    $pid = $cart_item['product_id'] ?? 0;
    if (!$pid || !has_term('add-on', 'product_cat', $pid)) return $name;

    // Ambil main plan
    $main_sub = phoenix_get_main_sub_from_uuid($tenant_uuid);
    if (!$main_sub) return $name;

    $next_payment = $main_sub->get_date('next_payment');
    if (!$next_payment) return $name;

    $now   = current_time('timestamp');
    $np_ts = strtotime($next_payment);
    if ($np_ts <= $now) return $name;

    // Pakai helper yang sama dengan Section 1 — consistent
    $period_days    = phoenix_prorate_get_period_days($main_sub);
    $days_remaining = (int) floor(($np_ts - $now) / DAY_IN_SECONDS);

    // Sama persis dengan grace period Section 1: -1 hari
    if ($days_remaining >= ($period_days - 1)) return $name;

    $save_pct = round((1 - $days_remaining / $period_days) * 100);
    if ($save_pct <= 0) return $name;

    $until_date = date('d M Y', $np_ts);

    $badge = sprintf(
        ' <span style="display:inline-block;background:#27ae60;color:#fff;font-size:11px;font-weight:700;
            padding:2px 8px;border-radius:20px;vertical-align:middle;margin-left:6px;">
            %s
        </span>
        <span style="display:block;font-size:11px;color:#888;margin-top:3px;">
            %s
        </span>',
        phoenix_text('prorate.cart_badge_save', $save_pct),
        phoenix_text('prorate.cart_badge_until', esc_html($until_date))
    );

    return $name . $badge;
}

// ================================================================
// SECTION 5: SYNC NEXT_PAYMENT ADDON SETELAH MAIN PLAN RENEWAL
// Hook: woocommerce_subscription_renewal_payment_complete
//
// Flow:
// Main plan renew 1 Feb → 1 Mar (sukses bayar)
// → cari semua addon subscription instance yang sama via UUID
// → update next_payment addon ke 1 Mar (sejajar main plan baru)
//
// Ini yang membuat semua addon per instance selalu sejajar
// meskipun renewal terjadi berkali-kali
// ================================================================
add_action('woocommerce_subscription_renewal_payment_complete', 'phoenix_sync_addon_np_after_renewal', 10, 2);
function phoenix_sync_addon_np_after_renewal($subscription, $last_order) {
    if (!function_exists('wcs_get_users_subscriptions')) return;

    // Pastikan ini main plan, bukan addon
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) return;
        $n       = strtolower($item->get_name());
        $is_plan = strpos($n, 'basic') !== false || strpos($n, 'standard') !== false
                || strpos($n, 'premium') !== false || strpos($n, 'custom') !== false
                || strpos($n, 'byo') !== false
                || in_array($item->get_product_id(), [58, 22, 76, 33]);
        if (!$is_plan) return;
    }

    $new_next = $subscription->get_date('next_payment');
    if (!$new_next) return;

    // Ambil tenant_uuid instance ini dari DB
    global $wpdb;
    $tenant_row = $wpdb->get_row($wpdb->prepare(
        "SELECT tenant_uuid FROM {$wpdb->prefix}wbssaas_tenants
         WHERE subscription_wc_id = %d LIMIT 1",
        $subscription->get_id()
    ));

    // Fallback: cari dari semua rows instance ini (oldest row)
    if (!$tenant_row || !$tenant_row->tenant_uuid) {
        $tenant_row = $wpdb->get_row($wpdb->prepare(
            "SELECT tenant_uuid FROM {$wpdb->prefix}wbssaas_tenants
             WHERE customer_id = %d ORDER BY created ASC LIMIT 1",
            $subscription->get_user_id()
        ));
    }

    $tenant_uuid = $tenant_row ? $tenant_row->tenant_uuid : null;
    if (!$tenant_uuid) return;

    // Cari semua addon subscription yang matched ke instance ini
    $all_user_subs = wcs_get_users_subscriptions($subscription->get_user_id());
    $synced        = 0;

    foreach ($all_user_subs as $sub) {
        if ($sub->get_id() === $subscription->get_id()) continue;
        if (!$sub->has_status('active')) continue;

        // Cek apakah ini addon
        $is_addon = false;
        foreach ($sub->get_items() as $item) {
            if (has_term('add-on', 'product_cat', $item->get_product_id())) {
                $is_addon = true;
                break;
            }
        }
        if (!$is_addon) continue;

        // Cek apakah addon ini milik instance yang sama via UUID matching (3 methods)
        $belongs = false;
        $oid     = $sub->get_parent_id();

        // Method 1: GFAPI form 64
        if (!$belongs && function_exists('GFAPI') && $oid) {
            $entries = GFAPI::get_entries(64, ['field_filters' => [
                ['key' => 'woocommerce_order_number', 'value' => $oid]
            ]]);
            if (!empty($entries) && ($entries[0]['1'] ?? '') === $tenant_uuid) {
                $belongs = true;
            }
        }

        // Method 2: order item meta
        if (!$belongs && $oid) {
            $order = wc_get_order($oid);
            if ($order) {
                foreach ($order->get_items() as $oitem) {
                    foreach ($oitem->get_meta_data() as $ometa) {
                        if ((string) $ometa->value === $tenant_uuid) {
                            $belongs = true;
                            break 2;
                        }
                    }
                }
            }
        }

        // Method 3: wpdb gf_entry_meta
        if (!$belongs && $oid) {
            $gf_val = $wpdb->get_var($wpdb->prepare(
                "SELECT em.meta_value
                 FROM {$wpdb->prefix}gf_entry_meta em
                 INNER JOIN {$wpdb->prefix}gf_entry e ON e.id = em.entry_id
                 WHERE e.id IN (
                     SELECT entry_id FROM {$wpdb->prefix}gf_entry_meta
                     WHERE meta_key = 'woocommerce_order_number' AND meta_value = %s
                 )
                 AND em.meta_key = '1' AND e.form_id = 64 LIMIT 1",
                (string) $oid
            ));
            if ($gf_val === $tenant_uuid) $belongs = true;
        }

        if (!$belongs) continue;

        // Addon milik instance ini — sync next_payment
        try {
            $sub->update_dates(['next_payment' => $new_next]);
            $sub->save();
            $sub->add_order_note(phoenix_text('prorate.note_addon_np_auto_synced', $new_next));
            $synced++;
        } catch (Exception $e) {
            error_log('phoenix_sync_addon_np_after_renewal error (sub #' . $sub->get_id() . '): ' . $e->getMessage());
        }
    }

    if ($synced > 0) {
        $subscription->add_order_note(phoenix_text('prorate.note_renewal_synced', $synced, $new_next));
    }
}

// ================================================================
// SECTION 6: SET HARGA NORMAL DI SUBSCRIPTION LINE ITEM SAAT DIBUAT
// Hook: woocommerce_checkout_subscription_created (priority 20,
//        jalan setelah Section 2 sync next_payment)
//
// CRITICAL: Tanpa section ini, WCS simpan harga prorated di subscription item.
// Saat renewal, WCS charge angka yang sama → semua renewal ke depan prorated!
//
// Fix: Langsung setelah subscription dibuat, update line item
// ke harga normal produk. Jadi:
// - First payment = prorated (via cart fee di Section 1)
// - Subscription line item = full price → renewal 2 dst charge normal ✅
//
// Hanya jalan untuk addon subscription dengan prorate session aktif.
// ================================================================
add_action('woocommerce_checkout_subscription_created', 'phoenix_set_addon_full_price_on_create', 20, 3);
function phoenix_set_addon_full_price_on_create($subscription, $order, $recurring_cart) {
    // Hanya addon subscription
    $is_addon = false;
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) {
            $is_addon = true;
            break;
        }
    }
    if (!$is_addon) return;

    // Hanya jalan kalau ada prorate session — artinya first payment ini prorated
    // Kalau tidak ada session (beli di hari pertama, grace skip), tidak perlu fix price
    $prorate_np = WC()->session ? WC()->session->get('addon_prorate_np', '') : '';
    if (!$prorate_np) return;

    // Ambil harga dari parent order — harga aktual yang user bayar dalam currency mereka
    // Lebih akurat dari product->get_price() yang bisa return default currency (CHF)
    $parent_order  = wc_get_order($subscription->get_parent_id());
    $parent_prices = [];
    if ($parent_order) {
        foreach ($parent_order->get_items() as $parent_item) {
            $key       = $parent_item->get_variation_id() ?: $parent_item->get_product_id();
            $recurring = $parent_item->get_meta('_recurring_amount');
            if ($recurring) {
                $parent_prices[$key] = (float) $recurring;
            } else {
                $qty_parent          = max(1, (int) $parent_item->get_quantity());
                $parent_prices[$key] = (float) $parent_item->get_subtotal() / $qty_parent;
            }
        }
    }

    $updated = false;
    foreach ($subscription->get_items() as $item) {
        if (!has_term('add-on', 'product_cat', $item->get_product_id())) continue;

        // Priority 1: harga dari parent order (currency-aware)
        $key        = $item->get_variation_id() ?: $item->get_product_id();
        $full_price = $parent_prices[$key] ?? 0;

        // Priority 2: WCML filter
        if ($full_price <= 0) {
            $product    = $item->get_product();
            $currency   = $subscription->get_currency() ?: get_woocommerce_currency();
            $base_price = $product ? (float) $product->get_regular_price() : 0;
            $full_price = $base_price > 0
                ? (float) apply_filters('wcml_raw_price_amount', $base_price, $currency)
                : 0;
        }

        // Priority 3: get_price fallback
        if ($full_price <= 0) {
            $product    = $item->get_product();
            $full_price = $product ? (float) $product->get_price() : 0;
        }

        if ($full_price <= 0) continue;

        $qty = max(1, (int) $item->get_quantity());

        // Set ke full price — ini yang WCS pakai untuk semua renewal berikutnya
        $item->set_subtotal($full_price * $qty);
        $item->set_total($full_price * $qty);
        $item->save();

        $updated = true;
    }

    if ($updated) {
        $subscription->calculate_totals();
        $subscription->save();
        $subscription->add_order_note(
            'Subscription line item set to full price. First payment was prorated via cart fee. All future renewals will be charged at full price.'
        );
    }
}

// ================================================================
// SECTION 7: RETROACTIVE SYNC — WITH LOOP GUARD
// Hook: woocommerce_subscription_date_updated
//
// Saat admin update next_payment main plan,
// semua addon dari instance SAMA ikut sync.
//
// Static $is_syncing flag mencegah infinite loop:
// update_dates() di dalam loop akan trigger hook ini lagi.
// ================================================================
add_action('woocommerce_subscription_date_updated', 'phoenix_retroactive_sync_safe', 10, 3);
function phoenix_retroactive_sync_safe($subscription, $date_type, $datetime) {
    if ($date_type !== 'next_payment') return;
    if (!function_exists('wcs_get_users_subscriptions')) return;

    // Guard: prevent loop — kalau sedang dalam proses sync, skip
    static $is_syncing = false;
    if ($is_syncing) return;

    // Pastikan ini main plan, bukan addon
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) return;
        $n       = strtolower($item->get_name());
        $is_plan = strpos($n, 'basic') !== false || strpos($n, 'standard') !== false
                || strpos($n, 'premium') !== false || strpos($n, 'custom') !== false
                || strpos($n, 'byo') !== false
                || in_array($item->get_product_id(), [58, 22, 76, 33]);
        if (!$is_plan) return;
    }

    $user_id  = $subscription->get_user_id();
    $all_subs = wcs_get_users_subscriptions($user_id);
    $new_next = $subscription->get_date('next_payment');
    $new_end  = $subscription->get_date('end');

    if (!$new_next) return;

    $is_syncing = true; // Set flag sebelum loop

    foreach ($all_subs as $sub) {
        if ($sub->get_id() === $subscription->get_id()) continue;
        if (!$sub->has_status('active')) continue;

        $has_addon = false;
        foreach ($sub->get_items() as $item) {
            if (has_term('add-on', 'product_cat', $item->get_product_id())) {
                $has_addon = true;
                break;
            }
        }
        if (!$has_addon) continue;

        // Validasi milik instance yang sama
        if (function_exists('phoenix_addon_belongs_to_main_plan')) {
            if (!phoenix_addon_belongs_to_main_plan($sub->get_id(), $subscription->get_id())) continue;
        }

        $dates = ['next_payment' => $new_next];
        if ($new_end) $dates['end'] = $new_end;

        try {
            $sub->update_dates($dates);
            $sub->save();
            $sub->add_order_note(
                'Next payment synced retroactively from main plan #' . $subscription->get_id() . ': ' . $new_next
            );
        } catch (Exception $e) {
            error_log('Retroactive addon sync error (sub #' . $sub->get_id() . '): ' . $e->getMessage());
        }
    }

    $is_syncing = false; // Reset flag setelah selesai
}

// ================================================================
// SECTION 8: TAG ORDER NOTE DI RENEWAL ORDER
// Hook: woocommerce_subscription_renewal_payment_complete (priority 5)
//
// Tambahkan note di renewal order supaya admin bisa bedain
// mana first renewal (setelah prorate) vs renewal ke-2+ (full price)
// ================================================================
add_action('woocommerce_subscription_renewal_payment_complete', 'phoenix_tag_renewal_order_note', 5, 2);
function phoenix_tag_renewal_order_note($subscription, $last_order) {
    if (!$last_order) return;

    // Hanya untuk addon subscription
    $is_addon = false;
    foreach ($subscription->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) {
            $is_addon = true;
            break;
        }
    }
    if (!$is_addon) return;

    $renewal_orders = $subscription->get_related_orders('ids', 'renewal');
    $renewal_count  = count($renewal_orders);
    $currency       = $last_order->get_currency();
    $total          = $last_order->get_total();

    if ($renewal_count === 1) {
        $last_order->add_order_note(sprintf(
            '🔵 First renewal after prorated first payment. Amount: %s %s (full price — no prorate).',
            $currency,
            number_format($total, 2)
        ));
    } else {
        $last_order->add_order_note(sprintf(
            '🟢 Renewal #%d — full price. Amount: %s %s.',
            $renewal_count,
            $currency,
            number_format($total, 2)
        ));
    }

    $last_order->save();
}

// ================================================================
// SECTION 9: TAG ORDER NOTE DI FIRST PAYMENT (PRORATED ORDER)
// Hook: woocommerce_checkout_order_created
//
// Tambahkan note di first payment order supaya admin tahu ini prorated
// ================================================================
add_action('woocommerce_checkout_order_created', 'phoenix_tag_first_payment_order_note', 10, 1);
function phoenix_tag_first_payment_order_note($order) {
    if (!WC()->session) return;

    // Hanya kalau ada prorate session aktif
    $prorate_np = WC()->session->get('addon_prorate_np', '');
    if (!$prorate_np) return;

    // Cek apakah order berisi addon
    $has_addon   = false;
    $addon_names = [];
    foreach ($order->get_items() as $item) {
        if (has_term('add-on', 'product_cat', $item->get_product_id())) {
            $has_addon     = true;
            $addon_names[] = $item->get_name();
        }
    }
    if (!$has_addon) return;

    $currency     = $order->get_currency();
    $total        = $order->get_total();
    $renewal_date = date('d M Y', strtotime($prorate_np));

    $order->add_order_note(phoenix_text('prorate.note_first_payment',
        implode(', ', $addon_names),
        $currency,
        number_format($total, 2),
        $renewal_date
    ));

    $order->save();
}

// ================================================================
// SECTION 10 & 11: MOVED TO upgrade-subscriptions.php (Section 8)
// Prorate upgrade antar main plan (extend next_payment) dihandle
// di Section 8 upgrade-subscriptions.php karena Section 8 sudah
// punya akses ke old_sub dan new subscription di hook yang tepat
// (woocommerce_subscription_status_updated) yang jalan SETELAH
// checkout_subscription_created — menghindari conflict/overwrite.
// ================================================================