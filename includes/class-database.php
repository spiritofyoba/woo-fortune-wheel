<?php

defined('ABSPATH') || exit;

class WOF_Database
{
    public static function init()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wof_spins';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            phone VARCHAR(20) NOT NULL,
            result VARCHAR(255) NOT NULL,
            ip VARCHAR(45),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
