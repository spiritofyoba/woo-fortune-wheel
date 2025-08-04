<?php

defined('ABSPATH') || exit;

class WOF_Ajax_Handler
{
    public function __construct()
    {
        add_action('wp_ajax_wof_spin', [__CLASS__, 'handleSpin']);
        add_action('wp_ajax_nopriv_wof_spin', [__CLASS__, 'handleSpin']);
        add_action('woocommerce_before_calculate_totals', [__CLASS__, 'calculate_cart']);
    }

    public static function calculate_cart($cart): void
    {
        foreach ($cart->get_cart() as $cart_item) {
            if (!empty($cart_item['wheel_gift'])) {
                $cart_item['data']->set_price(0);
            }
        }
    }

    /**
     * Handle the AJAX spin request and return a JSON response.
     *
     * @return void
     */
    public static function handleSpin(): void
    {
        try {
            self::validateRequest();

            $phone = sanitize_text_field($_POST['phone'] ?? '');
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $settings = json_decode(get_option('wof_settings_json', '{}'));
            $items = array_values($settings->wheel_items ?? []);

            if (!self::isValidPhone($phone)) {
                self::error('Некоректний номер телефону.');
            }

            if (self::hasAlreadySpun($phone)) {
                self::error('Цей номер вже брав участь. Заходь через тиждень і крутани знову');
            }

            $result = self::selectPrize();
            $prize_text = $items[$result]->text ?? '';

            ob_start();
            load_partial_with_vars(WOF_PLUGIN_PATH . '/templates/frontend/result.php', [
                'prize_text' => $prize_text,
            ]);
            $html = ob_get_clean();

            self::logSpin($phone, $items[$result]->text, $ip);
            self::applyReward($result);

            self::success([
                'result' => $result,
                'phone' => $phone,
                'html' => $html,
            ]);

        } catch (Exception $e) {
            self::error($e->getMessage());
        }
    }

    /**
     * Validate nonce to protect the request.
     */
    private static function validateRequest(): void
    {
        check_ajax_referer('wof_spin_nonce', 'nonce');
    }

    /**
     * Validate the phone number format.
     *
     * @param string $phone
     * @return bool
     */
    private static function isValidPhone(string $phone): bool
    {
        return preg_match('/^0\d{9}$/', $phone);
    }

    /**
     * Check if the user has already spun the wheel.
     *
     * @param string $phone
     * @return bool
     */
    private static function hasAlreadySpun(string $phone): bool
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wof_spins';

        $sevenDaysAgo = (new DateTime('-7 days'))->format('Y-m-d H:i:s');

        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$table}
         WHERE phone = %s
         AND created_at >= %s",
            $phone,
            $sevenDaysAgo
        ));

        return $count > 0;
    }

    /**
     * Select a prize index based on defined weights.
     *
     * @return int
     */
    private static function selectPrize(): int
    {
        $settings = json_decode(get_option('wof_settings_json', '{}'));
        $items = array_values($settings->wheel_items ?? []);

        $prizes = [];
        $weights = [];

        foreach ($items as $index => $item) {
            $prizes[] = $index;
            $weights[] = $item->rate;
        }

        $rand = mt_rand() / mt_getrandmax();
        $cumulative = 0;

        foreach ($weights as $i => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $prizes[$i];
            }
        }

        return $prizes[0]; // fallback
    }

    /**
     * Save spin result to database.
     *
     * @param string $phone
     * @param int $result
     * @param string $ip
     */
    private static function logSpin(string $phone, string $result, string $ip): void
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wof_spins';

        $wpdb->insert($table, [
            'phone' => $phone,
            'result' => $result,
            'ip' => $ip,
            'created_at' => current_time('mysql'),
        ]);
    }

    /**
     * Apply reward to WooCommerce cart.
     *
     * @param int $result
     */
    private static function applyReward(int $result)
    {
        if (class_exists('WOF_Cart_Integration')) {
            $settings = json_decode(get_option('wof_settings_json', '{}'));
            $items = array_values($settings->wheel_items ?? []);
            WOF_Cart_Integration::apply_reward($items[$result]);
        }
    }

    /**
     * Return success JSON response.
     *
     * @param array $data
     */
    private static function success(array $data): void
    {
        wp_send_json_success($data);
    }

    /**
     * Return error JSON response.
     *
     * @param string $message
     */
    private static function error(string $message): void
    {
        wp_send_json_error(['message' => $message]);
    }
}

new WOF_Ajax_Handler();
