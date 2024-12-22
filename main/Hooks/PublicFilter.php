<?php
/**
 * Main ActionHooks class.
 *
 * @package TinySolutions\wcqf
 */

namespace TinySolutions\wcqf\Hooks;

use TinySolutions\wcqf\Common\Loader;
use TinySolutions\wcqf\Traits\SingletonTrait;

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
	}
	/**
	 * Add quantity field
	 *
	 * @param string $html
	 * @param WC_Product $product
	 * @return string
	 */
	public function add_quantity_field($html, $product) {
		$custom_file  = '<div class="sc-quantity-wrapper wcqf-quantity-layout2">';
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
