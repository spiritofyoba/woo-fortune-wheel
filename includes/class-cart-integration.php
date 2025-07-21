<?php

defined('ABSPATH') || exit;

class WOF_Cart_Integration
{
    public static function apply_reward($result)
    {
        if (!class_exists('WC_Cart') || !WC()->cart) {
            return;
        }

        if ($result === '10% знижка') {
            $coupon_code = 'wheel_discount_' . wp_generate_password(4, false);
            $new_coupon_id = wp_insert_post([
                'post_title' => $coupon_code,
                'post_status' => 'publish',
                'post_type' => 'shop_coupon',
                'post_author' => 1,
            ]);

            update_post_meta($new_coupon_id, 'discount_type', 'percent');
            update_post_meta($new_coupon_id, 'coupon_amount', 10);
            update_post_meta($new_coupon_id, 'usage_limit', 1);

            WC()->cart->apply_coupon($coupon_code);

        } elseif ($result === 'Подарунок') {
            $variable_product_id = 4332;
            $variation_id = 4333;

            WC()->cart->add_to_cart(
                $variable_product_id,
                1,
                $variation_id,
                [],
                ['wheel_gift' => true]
            );
        } elseif ($result === 'Безкоштовна доставка') {
            $coupon_code = 'wheel_free_shipping_' . wp_generate_password(4, false);
            $new_coupon_id = wp_insert_post([
                'post_title' => $coupon_code,
                'post_status' => 'publish',
                'post_type' => 'shop_coupon',
                'post_author' => 1,
            ]);

            update_post_meta($new_coupon_id, 'discount_type', 'fixed_cart');
            update_post_meta($new_coupon_id, 'coupon_amount', 0);
            update_post_meta($new_coupon_id, 'free_shipping', 'yes');
            update_post_meta($new_coupon_id, 'usage_limit', 1);

            WC()->cart->apply_coupon($coupon_code);
        }
    }
}
