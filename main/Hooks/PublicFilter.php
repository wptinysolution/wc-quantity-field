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

defined( 'ABSPATH' ) || exit();

/**
 * Main ActionHooks class.
 */
class PublicFilter {
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
		$this->loader->add_filter( 'woocommerce_loop_add_to_cart_link', $this, 'add_quantity_field', 20, 2 );
		$this->loader->add_filter( 'woocommerce_cart_item_name', $this, 'add_quantity_field_checkout', 20, 3 );
		$this->loader->add_filter( 'woocommerce_checkout_cart_item_quantity', $this, 'hide_quantity_number', 20, 2 );
	}

	/**
	 * Add quantity field
	 *
	 * @param string $html
	 * @param WC_Product $cart_key
	 * @return string
	 */
    public function hide_quantity_number( $cart_product, $cart_key ){
        return $cart_product = '';
    }

	/**
	 * Add quantity field
	 *
	 * @param string $html
	 * @param WC_Product $product
	 * @return string
	 */
	public function add_quantity_field($html, $product ) {
        error_log( print_r( $product , true) . "\n\n", 3, __DIR__ . '/log.txt' );
        $api_value = Fns::get_options();
        $layout = !empty( $api_value['qtyLayout'] ) ? sanitize_text_field( $api_value['qtyLayout'] ) : '';
        $quantity_field = !empty($api_value['isShopShowQtyField']) && (int)$api_value['isShopShowQtyField'] === 1;
        if ( empty( $quantity_field ) || !$product || !$product->is_type('simple') || !$product->is_purchasable() || !$product->is_in_stock() || $product->is_sold_individually()) {
            return $html;
        }
        if( !empty( $quantity_field ) ) {
            $custom_file  = '<div class="wcqf-quantity-wrapper wcqf_'. esc_attr( $layout ) .'">';
            $custom_file .= woocommerce_quantity_input(
                [
                    'min_value'   => 1,
                    'input_value' => 1,
                    'step'        => 1,
                    'max_value'   => $product->get_stock_quantity(),
                ],
                $product,
                false
            );
            $custom_file .= $html;
            $custom_file  .= '</div>';
            return $custom_file;
        }
	}

    public function add_quantity_field_checkout( $product_title, $cart_item, $cart_item_key ) {
        $api_value = Fns::get_options();
        $checkout_field = !empty($api_value['isCheckoutShowQtyField']) && (int)$api_value['isCheckoutShowQtyField'] === 1;
        if ( is_checkout() && '1' == $checkout_field ) {
            $cart     = WC()->cart->get_cart();
            foreach ( $cart as $cart_key => $cart_value ){
                if ( $cart_key == $cart_item_key ){
                    $_product   = $cart_item['data'] ;
                    $return_value = '<div class="wcqf-checkout-inner">';
                    if ( $_product->is_sold_individually() ) {
                        $return_value .= sprintf( ' 1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_key );
                    } else {
                        $return_value .= woocommerce_quantity_input( array(
                            'input_name'  => "cart[{$cart_key}][qty]",
                            'input_value' => $cart_item['quantity'],
                            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(), $_product ),
                            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $_product ),
                            'pattern'     => '[0-9]*'
                        ), $_product , true );
                    }
                    $return_value .= '<span class = "cqoc_product_name" >' . $product_title . '</span>';
                    $return_value .= '</div>';
                    return $return_value;
                }
            }
            return $product_title;
        }else{
            return $product_title;
        }
    }
}
