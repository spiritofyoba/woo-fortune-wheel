<?php

defined('ABSPATH') || exit;

class WOF_Admin {

    function __construct() {
        add_action('admin_menu', [self::class, 'register_admin_menu']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueue_assets']);
    }

    public static function enqueue_assets($hook) {

        if ($hook !== 'toplevel_page_wof-submissions' && $hook !== 'wheel-of-fortune_page_wof-settings') {
            return;
        }

        wp_enqueue_style('wof-admin-css', WOF_PLUGIN_URL . 'assets/dist/css/admin.min.css');
        wp_enqueue_style('wof-bootstrap-css', WOF_PLUGIN_URL . 'assets/dist/css/bootstrap.min.css');
        wp_enqueue_script('wof-admin-js', WOF_PLUGIN_URL . 'assets/dist/js/admin.min.js', ['jquery'], null, true);
        wp_enqueue_script('wof-bootstrap-js', WOF_PLUGIN_URL . 'assets/dist/js/bootstrap.min.js', ['jquery'], null, true);

        wp_localize_script('wof-admin-js', 'WOF_JS', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wof_spin_nonce'),
        ]);
    }

    public static function register_admin_menu() {
        add_menu_page(
            'Wheel of Fortune',
            'Wheel of Fortune',
            'manage_options',
            'wof-submissions',
            [self::class, 'render_submissions_page'],
            'dashicons-image-filter',
            56
        );

        add_submenu_page(
            'wof-submissions', // <- цей slug використовується як parent
            'All Submissions',
            'All Submissions',
            'manage_options',
            'wof-submissions',
            [self::class, 'render_submissions_page']
        );

        add_submenu_page(
            'wof-submissions',
            'Plugin Settings',
            'Settings',
            'manage_options',
            'wof-settings',
            [self::class, 'render_settings_page']
        );
    }

    public static function render_submissions_page() {
        load_template(WOF_PLUGIN_PATH . 'templates/admin-submissions.php', true);
    }

    public static function render_settings_page() {
        load_template(WOF_PLUGIN_PATH . 'templates/admin-settings.php', true);
    }
}

new WOF_Admin();