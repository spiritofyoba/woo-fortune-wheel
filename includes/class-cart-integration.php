<?php

defined('ABSPATH') || exit;

/**
 * Handles the integration between the Wheel of Fortune rewards and WooCommerce Cart.
 */
class WOF_Cart_Integration
{
    /**
     * Apply the reward to the WooCommerce cart based on the result type.
     *
     * If the result is "discount", a temporary 10% discount coupon is created and applied.
     * If the result is something else, a gift product is added to the cart.
     *
     * @param object $result The reward result object, e.g., (object)['type' => 'gift', 'coupon' => 123]
     * @return void
     */
    public static function apply_reward($result)
    {
        if (!class_exists('WC_Cart') || !WC()->cart) {
            return;
        }

        if ($result->type === 'discount') {
            WC()->cart->apply_coupon($result->coupon);
        } else {
            $variation_id = (int) $result->coupon;
            $variation = wc_get_product($variation_id);

            if (!$variation || !$variation->is_type('variation')) {
                return;
            }

            $parent_id = $variation->get_parent_id();
            $variation_attributes = $variation->get_attributes(); // already formatted

            $cart_key = WC()->cart->add_to_cart(
                $parent_id,
                1,
                $variation_id,
                $variation_attributes,
                ['wheel_gift' => true]
            );

            if ($cart_key && isset(WC()->cart->cart_contents[$cart_key])) {
                WC()->cart->cart_contents[$cart_key]['data']->set_price(0);
            }

            WC()->cart->calculate_totals();

            return $cart_key;
        }
    }
}