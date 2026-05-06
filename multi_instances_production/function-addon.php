// Fungsi Meta Box untuk Atur Allowed Plans di Admin Product
add_action('add_meta_boxes', 'add_addon_plans_metabox');
function add_addon_plans_metabox() {
    add_meta_box('addon_allowed_plans', 'Addon Allowed Plans', 'render_addon_plans_metabox', 'product', 'side');
}

function render_addon_plans_metabox($post) {
    $value = get_post_meta($post->ID, '_addon_allowed_plans', true);
    echo '<label>Allowed Plans (contoh: basic,premium)</label><br>';
    echo '<input type="text" name="_addon_allowed_plans" value="' . esc_attr($value) . '" style="width:100%">';
}

add_action('save_post_product', 'save_addon_plans_metabox');
function save_addon_plans_metabox($post_id) {
    if (isset($_POST['_addon_allowed_plans'])) {
        update_post_meta($post_id, '_addon_allowed_plans', sanitize_text_field($_POST['_addon_allowed_plans']));
    }
}

add_action('woocommerce_product_duplicate', 'auto_set_addon_meta_on_duplicate', 10, 2);
function auto_set_addon_meta_on_duplicate($new_product, $original_product) {
    if (has_term('add-on', 'product_cat', $original_product->get_id())) {
        $original_meta = get_post_meta($original_product->get_id(), '_addon_allowed_plans', true);
        if ($original_meta) update_post_meta($new_product->get_id(), '_addon_allowed_plans', $original_meta);
    }
}

add_action('dp_duplicate_post', 'auto_set_addon_meta_yoast', 10, 2);
function auto_set_addon_meta_yoast($new_post_id, $post) {
    if ($post->post_type === 'product' && has_term('add-on', 'product_cat', $post->ID)) {
        $original_meta = get_post_meta($post->ID, '_addon_allowed_plans', true);
        if ($original_meta) update_post_meta($new_post_id, '_addon_allowed_plans', $original_meta);
    }
}