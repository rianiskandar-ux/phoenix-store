
/**
 * SNIPPET: GF Subdomain Uniqueness Guard — v3 (with debug)
 *
 * Set PHOENIX_GF_GUARD_DEBUG = true to write trace to wp-content/debug.log
 * Set back to false (or remove) before going live.
 */
define('PHOENIX_GF_GUARD_DEBUG', false);

// ── Field map: form_id => subdomain field id ──────────────────────────────────
if (!defined('PHOENIX_GF_SUBDOMAIN_FIELD_MAP')) {
    define('PHOENIX_GF_SUBDOMAIN_FIELD_MAP', [
        58 => 4,
        61 => 5,
        70 => 4,
    ]);
}

// ── Debug logger ─────────────────────────────────────────────────────────────
function phoenix_gf_guard_log($msg) {
    if (!defined('PHOENIX_GF_GUARD_DEBUG') || !PHOENIX_GF_GUARD_DEBUG) return;
    error_log('[GF-GUARD] ' . $msg);
}

// ── Hook registration — log when snippet loads ───────────────────────────────
phoenix_gf_guard_log('Snippet loaded. Registering gform_validation hook.');
add_filter('gform_validation', 'phoenix_gf_guard_subdomain_unique', 20);

/**
 * GF validation callback.
 */
function phoenix_gf_guard_subdomain_unique($validation_result) {
    $form    = $validation_result['form'];
    $form_id = (int) $form['id'];

    phoenix_gf_guard_log("gform_validation fired. form_id={$form_id}, is_valid=" . ($validation_result['is_valid'] ? 'true' : 'false'));

    $field_map = PHOENIX_GF_SUBDOMAIN_FIELD_MAP;

    if (!isset($field_map[$form_id])) {
        phoenix_gf_guard_log("form_id={$form_id} not in field map — skip.");
        return $validation_result;
    }

    $subdomain_field_id = $field_map[$form_id];

    // Try both rgpost and $_POST directly for robustness
    $subdomain = strtolower(trim(rgpost("input_{$subdomain_field_id}")));
    if (!$subdomain) {
        $subdomain = strtolower(trim($_POST["input_{$subdomain_field_id}"] ?? ''));
    }

    phoenix_gf_guard_log("field_id={$subdomain_field_id}, raw subdomain='" . $subdomain . "'");

    if (!$subdomain) {
        phoenix_gf_guard_log('Subdomain empty — skip.');
        return $validation_result;
    }

    if (strpos($subdomain, 'upgrade-') === 0) {
        phoenix_gf_guard_log('Upgrade bypass detected — skip.');
        return $validation_result;
    }

    $is_taken = phoenix_gf_is_subdomain_taken($subdomain);
    phoenix_gf_guard_log("is_taken=" . ($is_taken ? 'TRUE — blocking' : 'false — allow'));

    if ($is_taken) {
        $validation_result['is_valid'] = false;

        foreach ($form['fields'] as &$field) {
            if ((int) $field->id === $subdomain_field_id) {
                $field->failed_validation  = true;
                $field->validation_message = phoenix_gf_guard_error_msg($subdomain);
                phoenix_gf_guard_log("Field {$subdomain_field_id} marked failed_validation.");
                break;
            }
        }
        unset($field);
        $validation_result['form'] = $form;
    }

    return $validation_result;
}

/**
 * Check subdomain across GF entries (all statuses) + wbssaas_tenants table.
 */
function phoenix_gf_is_subdomain_taken($subdomain) {
    if (!$subdomain) return false;

    // ── Layer 1: GF entries ───────────────────────────────────────────────────
    if (class_exists('GFAPI')) {
        $field_map = PHOENIX_GF_SUBDOMAIN_FIELD_MAP;

        foreach ($field_map as $fid => $field_id) {
            foreach (['active', 'spam', 'trash'] as $status) {
                $entries = GFAPI::get_entries($fid, [
                    'field_filters' => [
                        ['key' => (string) $field_id, 'value' => $subdomain, 'operator' => 'is'],
                    ],
                    'status' => $status,
                ], null, ['page_size' => 5]);

                if (empty($entries)) continue;

                foreach ($entries as $entry) {
                    $val = strtolower(trim($entry[(string) $field_id] ?? ''));
                    if ($val === '' || strpos($val, 'upgrade-') === 0) continue;
                    if ($val === $subdomain) {
                        phoenix_gf_guard_log("MATCH in GF entries: form={$fid} field={$field_id} status={$status} val='{$val}'");
                        return true;
                    }
                }
            }
        }
        phoenix_gf_guard_log('No match in GF entries.');
    } else {
        phoenix_gf_guard_log('GFAPI not available — skipping Layer 1.');
    }

    // ── Layer 2: wbssaas_tenants table ───────────────────────────────────────
    global $wpdb;
    $table = $wpdb->prefix . 'wbssaas_tenants';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table'") !== $table) {
        phoenix_gf_guard_log('Tenants table not found — skip Layer 2.');
        return false;
    }

    $rows = $wpdb->get_col(
        "SELECT tenant_url FROM $table
         WHERE tenant_url != ''
         AND tenant_url NOT LIKE 'upgrade-%'
         LIMIT 1000"
    );

    foreach ($rows as $url) {
        $url  = preg_replace('/\.stg\./i', '.', $url);
        $host = parse_url($url, PHP_URL_HOST);          // tenant_url already has https://
        if (!$host) $host = $url;                       // fallback if stored without scheme
        $parts = explode('.', $host);
        $sub   = strtolower(trim($parts[0] ?? ''));
        if ($sub && $sub === $subdomain) {
            phoenix_gf_guard_log("MATCH in tenants table: url='{$url}' sub='{$sub}'");
            return true;
        }
    }

    phoenix_gf_guard_log('No match in tenants table either.');
    return false;
}

/**
 * User-facing error message.
 */
function phoenix_gf_guard_error_msg($subdomain) {
    if (function_exists('phoenix_text')) {
        $tmpl = phoenix_text('gf_guard.subdomain_taken');
        if ($tmpl && $tmpl !== 'gf_guard.subdomain_taken') {
            return sprintf($tmpl, esc_html($subdomain));
        }
    }
    return sprintf(
        'The subdomain &ldquo;%s&rdquo; is already registered. Please choose a different subdomain.',
        esc_html($subdomain)
    );
}
