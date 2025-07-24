<?php

defined('ABSPATH') || exit;

class WOF_Admin {

    function __construct() {
        add_action('admin_menu', [self::class, 'register_admin_menu']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueue_assets']);
        add_action('admin_init', [self::class, 'register_settings']);  // <-- register settings here
    }

    public static function register_settings() {
        register_setting('wof_settings_group', 'wof_settings_json');
    }

    public static function enqueue_assets($hook, $allowed_hooks = []) {
        if (!in_array($hook, $allowed_hooks, true)) {
            return;
        }

        wp_enqueue_style('wof-admin-css', WOF_PLUGIN_URL . 'assets/dist/css/admin.min.css');
        wp_enqueue_style('wof-bootstrap-css', WOF_PLUGIN_URL . 'assets/dist/css/bootstrap.min.css');
        wp_enqueue_script('wof-popper-js', WOF_PLUGIN_URL . 'assets/dist/js/popper.min.js', ['jquery'], null, true);
        wp_enqueue_script('wof-bootstrap-js', WOF_PLUGIN_URL . 'assets/dist/js/bootstrap.bundle.js', ['jquery'], null, true);
        wp_enqueue_script('wof-admin-js', WOF_PLUGIN_URL . 'assets/dist/js/admin.min.js', ['jquery'], null, true);

        wp_localize_script('wof-admin-js', 'WOF_JS', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wof_spin_nonce'),
        ]);
    }

    public static function register_admin_menu() {
        $main_hook = add_menu_page(
            'Колесо фортуни',
            'Колесо фортуни',
            'manage_options',
            'wof-submissions',
            [self::class, 'render_submissions_page'],
            'dashicons-image-filter',
            56
        );

        $submissions_hook = add_submenu_page(
            'wof-submissions',
            'All Submissions',
            __('Усі ліди', WOF_PLUGIN_TEXTDOMAIN),
            'manage_options',
            'wof-submissions',
            [self::class, 'render_submissions_page']
        );

        $settings_hook = add_submenu_page(
            'wof-submissions',
            'Plugin Settings',
            __('Налаштування', WOF_PLUGIN_TEXTDOMAIN),
            'manage_options',
            'wof-settings',
            [self::class, 'render_settings_page']
        );

        // Enqueue assets only on these hooks
        add_action('admin_enqueue_scripts', function ($hook) use ($main_hook, $settings_hook) {
            self::enqueue_assets($hook, [$main_hook, $settings_hook]);
        });
    }


    public static function render_submissions_page() {
        load_template(WOF_PLUGIN_PATH . 'templates/admin/admin-submissions.php', true);
    }

    public static function render_settings_page() {
        if (
            $_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['wof_settings_nonce']) &&
            wp_verify_nonce($_POST['wof_settings_nonce'], 'wof_save_settings') &&
            current_user_can('manage_options')
        ) {

            $wheel_items = isset($_POST['wheel_items']) ? sanitize_nested_array($_POST['wheel_items']) : [];

            $data = [
                'wheel_items' => $wheel_items,
            ];

            update_option('wof_settings_json', wp_json_encode($data));
            add_settings_error('wof_messages', 'wof_message', 'Settings saved.', 'updated');
        }

        $saved = get_option('wof_settings_json', '{}');
        $settings = json_decode($saved, true);

        set_query_var('wof_settings', $settings);

        load_template(WOF_PLUGIN_PATH . 'templates/admin/admin-settings.php', false);
    }
}

new WOF_Admin();
