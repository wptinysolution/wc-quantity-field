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
class Ajax {

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
		$this->loader->add_action( 'wp_ajax_wcqf_update_checkout_order_data', $this, 'wcqf_update_order_review' );
		$this->loader->add_action( 'wp_ajax_nopriv_wcqf_update_checkout_order_data', $this, 'wcqf_update_order_review' );
	}
	
	/**
	 * @return void
	 */
	public function wcqf_update_order_review() {
		if (!isset($_POST['cartItemKey'], $_POST['quantity'], $_POST['security']) ||
			!wp_verify_nonce($_POST['security'], 'wcqf_update_order_review_nonce')) {
			wp_send_json_error('Invalid request.');
		}
		
		$cart_item_key = sanitize_text_field($_POST['cartItemKey']);
		$quantity = intval($_POST['quantity']);
		
		WC()->cart->set_quantity($cart_item_key, $quantity);
		WC()->cart->calculate_totals();
		wp_send_json_success(['message' => 'Cart updated.']);
	}
	
	
	
}
