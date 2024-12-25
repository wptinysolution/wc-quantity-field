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
		$this->loader->add_filter( 'woocommerce_checkout_cart_item_quantity', $this, 'add_quantity_field_checkout', 20, 3 );
	}

	/**
	 * Add quantity field
	 *
	 * @param string     $html
	 * @param WC_Product $product
	 * @return string
	 */
	public function add_quantity_field( $html, $product ) {
		$api_value      = Fns::get_options();
		$layout         = ! empty( $api_value['qtyLayout'] ) ? sanitize_text_field( $api_value['qtyLayout'] ) : '';
		$quantity_field = ! empty( $api_value['isShopShowQtyField'] ) && (int) $api_value['isShopShowQtyField'] === 1;
		if ( empty( $quantity_field ) || ! $product || ! $product->is_type( 'simple' ) || ! $product->is_purchasable() || ! $product->is_in_stock() || $product->is_sold_individually() ) {
			return $html;
		}
		if ( ! empty( $quantity_field ) ) {
			$custom_file  = '<div class="wcqf-quantity-wrapper wcqf_' . esc_attr( $layout ) . '">';
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
			$custom_file .= '</div>';
			return $custom_file;
		}
	}

	public function add_quantity_field_checkout( $qty, $cart_item, $cart_item_key ) {
		$api_value      = Fns::get_options();
		$_product     = $cart_item['data'];
		if ( ! is_checkout() || $_product->is_sold_individually() || empty( $api_value['isCheckoutShowQtyField'] ) ) {
			return $qty;
		}
		$qty = '<div class="wcqf-checkout-inner" data-cart_item_key="'.$cart_item_key.'">';
		$qty .= woocommerce_quantity_input(
			[
				'min_value'   => 1,
				'input_value' => $cart_item['quantity'],
				'step'        => 1,
				'max_value'   => $_product->get_stock_quantity(),
			],
			$_product,
			false
		);
		$qty .= '</div>';
		return $qty;
	}
	
	
}
