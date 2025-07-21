<?php

defined('ABSPATH') || exit;

class WOF_Spinner
{
    function __construct()
    {
        add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
        add_action('wp_footer', [self::class, 'render_modal']);
    }

    public static function enqueue_assets()
    {
        wp_enqueue_style('wof-css', WOF_PLUGIN_URL . 'assets/dist/css/styles.min.css');
        wp_enqueue_script('wof-js', WOF_PLUGIN_URL . 'assets/dist/js/scripts.min.js', ['jquery'], null, true);

        wp_localize_script('wof-js', 'WOF_JS', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wof_spin_nonce'),
        ]);
    }

    public static function render_modal()
    {
        include WOF_PLUGIN_PATH . 'templates/modal.php';
    }
}

new WOF_Spinner();