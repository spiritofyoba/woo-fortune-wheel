<?php

defined('ABSPATH') || exit;

class WOF_Ajax_Handler
{
    function __construct()
    {
        add_action('wp_ajax_wof_spin', [__CLASS__, 'handle_spin']);
        add_action('wp_ajax_nopriv_wof_spin', [__CLASS__, 'handle_spin']);
    }

    public static function handle_spin()
    {
        check_ajax_referer('wof_spin_nonce', 'nonce');

        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';

        if (!preg_match('/^\+380\d{9}$/', $phone)) {
            wp_send_json_error(['message' => 'Некоректний номер телефону.']);
        }

        global $wpdb;
        $table = $wpdb->prefix . 'wof_spins';

        $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE phone = %s", $phone));
        if ($exists) {
            wp_send_json_error(['message' => 'Ви вже брали участь.']);
        }


        $prizes = [
            0,
            1,
            2,
            3,
            4,
            5,
            6,
            7
        ];

        $weights = [0.2, 0.15, 0.15, 0.1, 0.1, 0.1, 0.1, 0.1];
        $rand = mt_rand() / mt_getrandmax();
        $cumulative = 0;
        $result = 'Спробуйте ще';

        foreach ($weights as $i => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                $result = $prizes[$i];
                break;
            }
        }
//
//        $wpdb->insert($table, [
//            'phone' => $phone,
//            'result' => $result,
//            'ip' => $ip,
//            'created_at' => current_time('mysql'),
//        ]);

//        WOF_Cart_Integration::apply_reward($result);

        wp_send_json_success(['result' => $result]);
    }
}

new WOF_Ajax_Handler();