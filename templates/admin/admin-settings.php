<?php
$wof_settings = get_query_var('wof_settings', []);
$wheel_items = $wof_settings['wheel_items'] ?? [];
$titles = $wof_settings['secondary_titles'] ?? [];
$main_title = $wof_settings['main_title'] ?? '';
$enable_plugin = $wof_settings['enable_plugin'] ?? true;
$resultText = $wof_settings['result_text'] ?? '';

if (!is_array($wheel_items) || empty($wheel_items)) {
    $wheel_items = [
        [
            'type' => '0',
            'coupon' => '0',
            'text' => '0',
        ],
    ];
}
?>

<div id="wof-wrapper">
    <div class="container">
        <div class="row">
            <h1 class="mb-3 mt-5 p-0"><?php echo __('Налаштування', WOF_PLUGIN_TEXTDOMAIN); ?></h1>

            <?php load_partial_with_vars(WOF_PLUGIN_PATH . 'templates/admin/partials/admin-alert.php'); ?>
        </div>
    </div>

    <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=wof-settings')); ?>">

        <?php
        load_partial_with_vars(WOF_PLUGIN_PATH . 'templates/admin/partials/admin-enable.php', [
            'enable' => $enable_plugin,
        ]);

        load_partial_with_vars(WOF_PLUGIN_PATH . 'templates/admin/partials/admin-wheel.php', [
            'wheel_items' => $wheel_items,
        ]);

        load_partial_with_vars(WOF_PLUGIN_PATH . 'templates/admin/partials/admin-texts.php', [
            'titles' => $titles,
            'main_title' => $main_title,
            'result_text' => $resultText,
        ]);

        load_partial_with_vars(WOF_PLUGIN_PATH . 'templates/admin/partials/admin-styles.php', []);
        ?>

        <div class="container">
            <div class="row">
                <p class="submit p-0">
                    <button type="submit" name="wof_save_settings" id="submit" class="btn btn-primary">
                        <?php esc_html_e('Зберегти налаштування', WOF_PLUGIN_TEXTDOMAIN); ?>
                    </button>
                </p>
            </div>
        </div>
    </form>
</div>
