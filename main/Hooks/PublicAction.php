<?php
/**
 * Main ActionHooks class.
 *
 * @package TinySolutions\wcqf
 */

namespace TinySolutions\wcqf\Hooks;

use TinySolutions\wcqf\Common\Loader;
use TinySolutions\wcqf\Traits\SingletonTrait;
use TinySolutions\wcqf\Helpers\Fns;

defined('ABSPATH') || exit();

/**
 * Main ActionHooks class.
 */
class PublicAction {
    /**
     * @var object
     */
    protected $loader;
    /**
     * Singleton
     */
    use SingletonTrait;

    /**
     * Class Constructor
     */
    private function __construct() {
        $this->loader = Loader::instance();
        add_action('wp_loaded', [$this, 'remove_default_quantity_field'], 20);
        add_action('woocommerce_loop_add_to_cart_link', [$this, 'add_quantity_field'], 20, 2);
        add_action('woocommerce_before_add_to_cart_button', [$this, 'add_quantity_field_single_page'], 20);
    }

    /**
     * Remove the default quantity field from single product page.
     */
    public function remove_default_quantity_field() {
        remove_action('woocommerce_before_add_to_cart_button', 'woocommerce_quantity_input', 10);
    }

    /**
     * Generate custom quantity field HTML.
     */
    private function generate_quantity_field($product, $layout, $options) {
        $qty_field = woocommerce_quantity_input(
            [
                'min_value'   => 1,
                'input_value' => 1,
                'step'        => 1,
                'max_value'   => $product->get_stock_quantity(),
            ],
            $product,
            false
        );

        $custom_html = '';

        if ($layout === 'layout1') {
            $custom_html .= '<div class="sc-quantity-wrapper wcqf-quantity-layout2">';
            $custom_html .= '<div class="quantity-buttons">';
            $custom_html .= '<button type="button" class="qty-minus">-</button>';
            $custom_html .= $qty_field;
            $custom_html .= '<button type="button" class="qty-plus">+</button>';
            $custom_html .= '</div>';
            $custom_html .= '</div>';
        } else {
            $custom_html .= '<div class="sc-quantity-wrapper wcqf-quanity-text">';
            $custom_html .= $qty_field;
            $custom_html .= '</div>';
            $quantity_text = !empty($options['qtyText']) ? sanitize_text_field($options['qtyText']) : esc_html__('Quantity', 'wc-quantity-field');
            $show_text = !empty($options['isShopShowQtyText']) && (int) $options['isShopShowQtyText'] === 1;
            if ($show_text) {
                $custom_html .= '<style>
                    .wcqf-quanity-text .quantity:before {
                        content: "' . esc_html($quantity_text) . ':";
                    }
                </style>';
            }
        }
        return $custom_html;
    }

    /**
     * Add custom quantity field to shop loop.
     *
     * @param string $html
     * @param object $product
     * @return string
     */
    public function add_quantity_field($html, $product) {
        if (!$product || !$product->is_type('simple') || !$product->is_purchasable() || !$product->is_in_stock() || $product->is_sold_individually()) {
            return $html;
        }
        $options = Fns::get_options();
        $layout = $options['qtyLayout'] ?? '';
        return $this->generate_quantity_field($product, $layout, $options) . $html;
    }

    /**
     * Add custom quantity field to single product page.
     */
    public function add_quantity_field_single_page() {
        global $product;
        if (!$product || !$product->is_type('simple') || !$product->is_purchasable() || !$product->is_in_stock() || $product->is_sold_individually()) {
            return;
        }
        $options = Fns::get_options();
        $layout = $options['qtyLayout'] ?? '';
        echo $this->generate_quantity_field($product, $layout, $options);
    }
}
