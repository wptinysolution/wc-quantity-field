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
	}

	/**
	 * add quantity field
	 * return $html
	 * */
	public function add_quantity_field( $html, $product ) {

		$custom_file = $html;
		if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
			$api_value = Fns::get_options();
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
			if ( $api_value['qtyLayout'] === 'layout1' ) {
				$custom_file  = '<div class="sc-quantity-wrapper wcqf-quantity-layout2">';
				$custom_file .= '<div class="quantity-buttons">';
				$custom_file .= '<button type="button" class="qty-minus">-</button>';
				$custom_file .= $qty_field;
				$custom_file .= '<button type="button" class="qty-plus">+</button>';
				$custom_file .= '</div>';
				$custom_file .= $html;
				$custom_file .= '</div>';
			} else {
				$custom_file   = '<div class="sc-quantity-wrapper wcqf-quanity-text">';
				$custom_file  .= $qty_field;
				$custom_file  .= $html;
				$custom_file  .= '</div>';
				$quantity_text = ! empty( $api_value['qtyText'] ) ? sanitize_text_field( $api_value['qtyText'] ) : esc_html__( 'Quantity', 'wc-quantity-field' );
				$quatity_field = ! empty( $api_value['isShopShowQtyText'] ) && (int) $api_value['isShopShowQtyText'] === 1;
				if ( $quatity_field ) {
					$custom_file .= '<style>
                    .wcqf-quanity-text .quantity:before {
                      content:"' . esc_html( $quantity_text ) . ':";
                    }
                </style>';
				}
			}
		}
		return $custom_file;
	}
}
