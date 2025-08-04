<?php

defined('ABSPATH') || exit;

/**
 * Class WOF_Spinner
 *
 * Handles the frontend spinner: loads assets and renders modal.
 */
class WOF_Spinner
{
    /**
     * Register WordPress hooks for frontend.
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
        add_action('wp_footer', [self::class, 'render_modal']);
    }

    /**
     * Enqueue necessary CSS and JS for the spinner on the frontend.
     * Localizes AJAX URL, nonce, and wheel items data for use in JS.
     *
     * @return void
     */
    public static function enqueue_assets(): void
    {
        wp_enqueue_style('wof-css', WOF_PLUGIN_URL . 'assets/dist/css/styles.min.css');
        wp_enqueue_script('inputmask','https://unpkg.com/inputmask@5.0.8/dist/inputmask.min.js',[],null,true);
        wp_enqueue_script('wof-js', WOF_PLUGIN_URL . 'assets/dist/js/scripts.min.js', ['jquery'], null, true);

        wp_localize_script('wof-js', 'WOF_JS', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wof_spin_nonce'),
            'wheelItems' => self::handle_wheel_items(),
        ]);
    }

    /**
     * Returns an array of wheel items (type and text) to be passed to JS.
     *
     * @return array
     */
    public static function handle_wheel_items(): array
    {
        $return_items = [];
        $wheel_items = json_decode(get_option('wof_settings_json', '{}'), true);

        foreach ($wheel_items['wheel_items'] ?? [] as $wheel_item) {
            $return_items[] = [
                'type' => $wheel_item['type'],
                'text' => $wheel_item['text'],
            ];
        }

        return $return_items;
    }

    /**
     * Render the modal HTML template in the footer.
     *
     * @return void
     */
    public static function render_modal(): void
    {
        include WOF_PLUGIN_PATH . 'templates/frontend/modal.php';
    }
}

new WOF_Spinner();