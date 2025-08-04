<?php

defined('ABSPATH') || exit;

/**
 * Admin class for the Wheel of Fortune plugin.
 * Handles menu registration, settings page rendering, and asset loading.
 */
class WOF_Admin
{
    public function __construct()
    {
        add_action('admin_menu', [self::class, 'registerAdminMenu']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueueAssets']);
        add_action('admin_init', [self::class, 'registerSettings']);
    }

    /**
     * Registers a WordPress setting to store plugin configuration as JSON.
     */
    public static function registerSettings(): void
    {
        register_setting('wof_settings_group', 'wof_settings_json');
    }

    /**
     * Enqueues required styles and scripts for the admin interface.
     *
     * @param string $hook Current admin page hook.
     * @param array $allowed_hooks Hooks where assets should be loaded.
     */
    public static function enqueueAssets($hook, $allowed_hooks = []): void
    {
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
            'nonce' => wp_create_nonce('wof_spin_nonce'),
        ]);
    }

    /**
     * Registers admin menu and submenu pages for the plugin.
     */
    public static function registerAdminMenu(): void
    {
        $mainHook = add_menu_page(
            'Колесо фортуни',
            'Колесо фортуни',
            'manage_options',
            'wof-submissions',
            [self::class, 'renderSubmissionsPage'],
            'dashicons-image-filter',
            56
        );

        $submissions_hook = add_submenu_page(
            'wof-submissions',
            'All Submissions',
            __('Усі ліди', WOF_PLUGIN_TEXTDOMAIN),
            'manage_options',
            'wof-submissions',
            [self::class, 'renderSubmissionsPage']
        );

        $settingsHook = add_submenu_page(
            'wof-submissions',
            'Plugin Settings',
            __('Налаштування', WOF_PLUGIN_TEXTDOMAIN),
            'manage_options',
            'wof-settings',
            [self::class, 'renderSettingsPage']
        );

        add_action('admin_enqueue_scripts', function ($hook) use ($mainHook, $settingsHook) {
            self::enqueueAssets($hook, [$mainHook, $settingsHook]);
        });
    }

    /**
     * Renders the submissions admin page.
     */
    public static function renderSubmissionsPage(): void
    {
        load_template(WOF_PLUGIN_PATH . 'templates/admin/admin-submissions.php', true);
    }

    /**
     * Handles saving plugin settings and renders the settings page.
     */
    public static function renderSettingsPage(): void
    {
        if (
            $_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_POST['wof_settings_nonce']) &&
            wp_verify_nonce($_POST['wof_settings_nonce'], 'wof_save_settings') &&
            current_user_can('manage_options')
        ) {
            $wheelItems = isset($_POST['wheel_items']) ? sanitize_nested_array($_POST['wheel_items']) : [];
            $secondaryTitles = isset($_POST['secondary_titles']) ? sanitize_nested_array($_POST['secondary_titles']) : [];
            $mainTitle = isset($_POST['main_title']) ? sanitize_text_field($_POST['main_title']) : '';
            $resultText = isset($_POST['result_text']) ? sanitize_text_field($_POST['result_text']) : '';
            $enablePlugin = isset($_POST['enable_plugin']) ? true : false;

            $data = [
                'wheel_items' => $wheelItems,
                'main_title' => $mainTitle,
                'secondary_titles' => $secondaryTitles,
                'enable_plugin' => $enablePlugin,
                'result_text' => $resultText
            ];

            update_option('wof_settings_json', wp_json_encode($data));
            add_settings_error('wof_messages', 'wof_message', 'Settings saved.', 'updated');
        }

        $settings = json_decode(get_option('wof_settings_json', '{}'), true);
        set_query_var('wof_settings', $settings);

        load_template(WOF_PLUGIN_PATH . 'templates/admin/admin-settings.php', false);
    }
}

new WOF_Admin();