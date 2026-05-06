<?php
/**
 * Phoenix Test Helper — Set _commitment_start_date for testing
 * HAPUS FILE INI SETELAH SELESAI TEST
 */

// Basic security: harus login sebagai admin
if (!defined('ABSPATH')) {
    // Load WordPress
    $wp_load = dirname(__FILE__);
    while (!file_exists($wp_load . '/wp-load.php') && $wp_load !== '/') {
        $wp_load = dirname($wp_load);
    }
    require_once $wp_load . '/wp-load.php';
}

if (!current_user_can('manage_woocommerce')) {
    wp_die('Access denied.');
}

$message = '';
$current_meta = '';

$sub_id  = isset($_POST['sub_id'])  ? (int) $_POST['sub_id']  : (isset($_GET['sub_id'])  ? (int) $_GET['sub_id']  : 0);
$months  = isset($_POST['months'])  ? (int) $_POST['months']  : 6;
$action  = isset($_POST['action_type']) ? $_POST['action_type'] : '';

if ($sub_id && $action === 'set') {
    $ts = strtotime('-' . $months . ' months');
    update_post_meta($sub_id, '_commitment_start_date', $ts);
    $message = "✅ Set _commitment_start_date = " . date('d M Y H:i', $ts) . " ($ts) on subscription #$sub_id";
}

if ($sub_id && $action === 'delete') {
    delete_post_meta($sub_id, '_commitment_start_date');
    $message = "🗑️ Deleted _commitment_start_date from subscription #$sub_id";
}

if ($sub_id) {
    $val = get_post_meta($sub_id, '_commitment_start_date', true);
    $current_meta = $val ? date('d M Y H:i', (int)$val) . " (ts: $val)" : '— not set —';

    // Also show commitment progress if function exists
    if (function_exists('wcs_get_subscription') && function_exists('phoenix_get_commitment_progress')) {
        $sub = wcs_get_subscription($sub_id);
        if ($sub) {
            $progress = phoenix_get_commitment_progress($sub);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Phoenix Test — Commitment Date</title>
<style>
body { font-family: monospace; max-width: 600px; margin: 40px auto; padding: 20px; background: #f5f5f5; }
h2 { color: #c0392b; }
.warning { background: #ffebee; border: 2px solid #c0392b; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 13px; }
.message { background: #e8f5e9; border: 1px solid #4caf50; padding: 10px; border-radius: 4px; margin: 12px 0; }
.card { background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 20px; margin-bottom: 16px; }
label { display: block; margin-bottom: 6px; font-weight: bold; font-size: 13px; }
input[type=number] { width: 100%; padding: 7px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
button { padding: 8px 18px; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; margin-right: 8px; }
.btn-set { background: #2196F3; color: #fff; }
.btn-delete { background: #f44336; color: #fff; }
.info { font-size: 12px; color: #666; margin-top: 8px; }
.progress { background: #ffe082; border-radius: 20px; height: 8px; overflow: hidden; margin: 6px 0; }
.progress-fill { height: 8px; border-radius: 20px; background: linear-gradient(90deg,#f39c12,#27ae60); }
</style>
</head>
<body>

<h2>⚠️ Phoenix Test Helper</h2>
<div class="warning">
    <strong>HAPUS FILE INI SETELAH SELESAI TEST!</strong><br>
    Path: <code><?php echo __FILE__; ?></code>
</div>

<?php if ($message): ?>
<div class="message"><?php echo esc_html($message); ?></div>
<?php endif; ?>

<div class="card">
    <form method="post">
        <label>Subscription ID</label>
        <input type="number" name="sub_id" value="<?php echo esc_attr($sub_id); ?>" placeholder="e.g. 12345" required>

        <label>Set start date ke N bulan lalu</label>
        <input type="number" name="months" value="<?php echo esc_attr($months); ?>" min="1" max="24">

        <div>
            <button type="submit" name="action_type" value="set" class="btn-set">Set Date</button>
            <button type="submit" name="action_type" value="delete" class="btn-delete">Delete Meta (reset)</button>
        </div>

        <div class="info">
            Preset: 6 bulan = progress 6/12 | 11 bulan = cancel window terbuka | 12+ bulan = free
        </div>
    </form>
</div>

<?php if ($sub_id): ?>
<div class="card">
    <strong>Current meta pada sub #<?php echo $sub_id; ?></strong><br>
    <code>_commitment_start_date</code> = <?php echo esc_html($current_meta); ?>

    <?php if (!empty($progress)): ?>
    <br><br>
    <strong>phoenix_get_commitment_progress():</strong><br>
    Months: <strong><?php echo $progress['months']; ?>/12</strong>
    &nbsp;|&nbsp; Complete: <strong><?php echo $progress['complete'] ? 'YES' : 'NO'; ?></strong>
    <div class="progress">
        <div class="progress-fill" style="width:<?php echo min(100, $progress['percentage']); ?>%"></div>
    </div>
    <?php if (function_exists('phoenix_get_cancel_window') && isset($sub)): ?>
    Cancel window: <strong><?php echo phoenix_get_cancel_window($sub); ?></strong>
    <?php endif; ?>
    <?php endif; ?>
</div>
<?php endif; ?>

</body>
</html>
