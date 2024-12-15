<?php
/**
 * Main ActionHooks class.
 *
 * @package TinySolutions\wp_quantity_field
 */

namespace TinySolutions\wp_quantity_field\Hooks;

use TinySolutions\wp_quantity_field\Common\Loader;
use TinySolutions\wp_quantity_field\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

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
		add_action( 'woocommerce_loop_add_to_cart_link', [ $this, 'add_quantity_field' ], 20, 2 );
		add_action( 'woocommerce_quantity_input_args', [ $this, 'sc_quantity_input_args' ], 20, 2 );
	}

	/**
	 * add quantity field
	 * return $html
	 * */
	public function add_quantity_field( $html, $product ) {
		$custom_file = $html;
		if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
			$custom_file  = '<div class="sc-quantity-wrapper">';
			$custom_file .= woocommerce_quantity_input( [], $product, false );
			$custom_file .= $html;
			$custom_file .= '</div>';
		}
		return $custom_file;
	}

	/**
	 * add max and min quantity value
	 * return $arges
	 */
	public function sc_quantity_input_args( $args, $product ) {
		if ( is_singular( 'product' ) && $product->is_type( 'simple' ) && $product->is_purchasable() & $product->is_in_stock() && ! $product->is_sold_individually() ) {
			$args['input_value'] = 1;
		}
		$args['max_value'] = $product->get_stock_quantity();
		$args['min_value'] = 0;
		$args['step']      = 1;
		return $args;
	}
}
