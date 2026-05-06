
/**
 * SNIPPET: Override WBS SaaS Tenants Shortcode + Redirect After Login
 * 
 * 1. Redirects old [wbsaas_tenants] shortcode to My SaaS Instances page
 * 2. Redirect after login → My SaaS (bypass plugin WBS SaaS page)
 * 3. Admin/shop_manager tetap ke dashboard WP
 */

// ================================================================
// 1. Override shortcode [wbsaas_tenants]
// ================================================================
remove_shortcode('wbsaas_tenants');
add_shortcode('wbsaas_tenants', 'phoenix_override_wbsaas_tenants');
function phoenix_override_wbsaas_tenants() {
    if (!is_user_logged_in()) {
        return '<div style="background:#fff3cd;padding:20px;border-radius:8px;text-align:center;">
            <strong>⚠️ Please log in</strong><br>
            <p style="margin:10px 0 15px 0;">Log in to view your SaaS instances.</p>
            <a href="' . esc_url(wp_login_url(get_permalink())) . '" class="button">Log In</a>
        </div>';
    }

    $my_saas_url = home_url('/my-account/workspaces/');

    // Kalau user sudah pernah lihat splash → langsung redirect tanpa tampilkan splash
    $user_id  = get_current_user_id();
    $seen_key = '_phoenix_seen_upgrade_splash';
    if (get_user_meta($user_id, $seen_key, true)) {
        wp_redirect($my_saas_url);
        exit;
    }

    // Pertama kali → tandai sudah lihat, tampilkan splash
    update_user_meta($user_id, $seen_key, '1');

    return '
    <div style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);padding:40px;border-radius:12px;text-align:center;color:#fff;margin:20px 0;">
        <div style="font-size:48px;margin-bottom:15px;">🎉</div>
        <h2 style="margin:0 0 10px 0;color:#fff;font-size:28px;">We\'ve Upgraded!</h2>
        <p style="margin:0 0 25px 0;font-size:16px;opacity:0.95;">
            Manage your SaaS instances with our new enhanced interface.
        </p>
        
        <div style="background:rgba(255,255,255,0.2);padding:20px;border-radius:8px;margin-bottom:25px;">
            <strong style="display:block;margin-bottom:10px;font-size:18px;">✨ New Features:</strong>
            <ul style="list-style:none;padding:0;margin:0;text-align:left;display:inline-block;">
                <li style="padding:5px 0;">✅ Multi-instance management</li>
                <li style="padding:5px 0;">✅ 12-month commitment tracker</li>
                <li style="padding:5px 0;">✅ Active add-ons summary</li>
                <li style="padding:5px 0;">✅ One-click upgrade options</li>
            </ul>
        </div>
        
        <a href="' . esc_url($my_saas_url) . '" 
           style="display:inline-block;padding:15px 30px;background:#fff;color:#667eea;text-decoration:none;border-radius:8px;font-weight:700;font-size:16px;box-shadow:0 4px 6px rgba(0,0,0,0.2);">
            View My SaaS Instances →
        </a>
        
        <p style="margin:20px 0 0 0;font-size:13px;opacity:0.8;">
            Redirecting automatically in <span id="countdown">3</span> seconds...
        </p>
    </div>
    
    <script>
    (function() {
        var count = 3;
        var countdown = document.getElementById("countdown");
        var interval = setInterval(function() {
            count--;
            if (countdown) countdown.textContent = count;
            if (count <= 0) {
                clearInterval(interval);
                window.location.href = "' . esc_url($my_saas_url) . '";
            }
        }, 1000);
    })();
    </script>
    ';
}

// ================================================================
// 2. Redirect after login → My SaaS page
// ================================================================
add_filter('woocommerce_login_redirect', 'phoenix_redirect_after_login', 10, 2);
add_filter('login_redirect', 'phoenix_redirect_after_login', 10, 3);

function phoenix_redirect_after_login($redirect, $user_or_requested = null, $user = null) {
    // Resolve user object
    if ($user instanceof WP_User) {
        $u = $user;
    } elseif ($user_or_requested instanceof WP_User) {
        $u = $user_or_requested;
    } else {
        $u = wp_get_current_user();
    }

    if (!$u || !$u->exists()) return $redirect;

    // Admin/shop_manager tetap ke dashboard
    if (in_array('administrator', (array) $u->roles)) return $redirect;
    if (in_array('shop_manager', (array) $u->roles)) return $redirect;

    // Semua customer/subscriber → My SaaS
    return home_url('/my-account/workspaces/');
}