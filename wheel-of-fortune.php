<?php
/**
 * Plugin Name: Noolabs Wheel of Fortune
 * Description: Wheel of Fortune for WooCommerce with phone number, winnings and analytics.
 * Version: 1.0
 * Author: vklymenko
 */

defined('ABSPATH') || exit;

add_action('plugins_loaded', function () {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>' . __("<strong>Noolabs Wheel of Fortune</strong> requires WooCommerce to be installed and activated.", "noolabs") . '</p></div>';
        });
        return;
    }

    define('WOF_PLUGIN_PATH', plugin_dir_path(__FILE__));
    define('WOF_PLUGIN_URL', plugin_dir_url(__FILE__));
    define('WOF_PLUGIN_TEXTDOMAIN', 'woo-fortune-wheel');

    require_once WOF_PLUGIN_PATH . 'includes/helpers.php';
    require_once WOF_PLUGIN_PATH . 'includes/class-database.php';
    require_once WOF_PLUGIN_PATH . 'includes/class-admin.php';

    if (!enable_disable_plugin()) return;

    require_once WOF_PLUGIN_PATH . 'includes/class-spinner.php';
    require_once WOF_PLUGIN_PATH . 'includes/class-ajax-handler.php';
    require_once WOF_PLUGIN_PATH . 'includes/class-cart-integration.php';


    register_activation_hook(__FILE__, ['WOF_Database', 'init']);
});
