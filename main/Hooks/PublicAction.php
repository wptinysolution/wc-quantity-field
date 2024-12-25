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
		$this->loader->add_action( 'woocommerce_before_add_to_cart_form', $this, 'before_sinlge_quantity_wrapper_div' );
		$this->loader->add_action( 'woocommerce_after_add_to_cart_form', $this, 'after_sinlge_quantity_wrapper_div' );
		$this->loader->add_action( 'woocommerce_before_quantity_input_field', $this, 'before_quantity_input_field' );
		$this->loader->add_action( 'woocommerce_after_quantity_input_field', $this, 'after_quantity_input_field' );
	}

	/**
	 * Add product single page before wrapper div
	 *
	 * @return string
	 */
	public function before_sinlge_quantity_wrapper_div() {
		$api_value = Fns::get_options();
		$layout    = ! empty( $api_value['qtyLayout'] ) ? sanitize_text_field( $api_value['qtyLayout'] ) : '';
		?>
		<div class="wcqf-quantity-wrapper wcqf_<?php echo esc_attr( $layout ); ?>">
		<?php
	}

	/**
	 * Add product single page after wrapper div
	 *
	 * @return string
	 */
	public function after_sinlge_quantity_wrapper_div() {
		?>
		</div>
		<?php
	}

	/**
	 * Add product quantity text and arror buttons
	 *
	 * @return string
	 */
	public function before_quantity_input_field() {
		$api_value       = Fns::get_options();
		$layout          = ! empty( $api_value['qtyLayout'] ) ? sanitize_text_field( $api_value['qtyLayout'] ) : '';
		$quantity_text   = ! empty( $api_value['qtyText'] ) ? sanitize_text_field( $api_value['qtyText'] ) : esc_html__( 'Quantity', 'wc-quantity-field' );
		$quantity_field  = ! empty( $api_value['isShopShowQtyText'] ) && (int) $api_value['isShopShowQtyText'] === 1;
		$product_text    = ! empty( $api_value['isProductShowQtyText'] ) && (int) $api_value['isProductShowQtyText'] === 1;
		$cart_field      = ! empty( $api_value['isCartShowQtyField'] ) && (int) $api_value['isCartShowQtyField'] === 1;
		$quantity_single = ! empty( $api_value['isProductShowQtyField'] ) && (int) $api_value['isProductShowQtyField'] === 1;
		if ( ( is_product() && $quantity_single != 1 ) || ( is_cart() && $cart_field != 1 ) ) {
			return;
		}
		if ( ! empty( $quantity_text ) && ! ( is_product() && $product_text != 1 ) && ! ( is_shop() && $quantity_field != 1 ) && ! is_cart() && ! is_checkout() ) {
			?>
			<div class="wcqf-quantity-text">
				<?php echo esc_html( $quantity_text ); ?>
			</div>
			<?php
		}
		?>
		<div class="wcqf-btn-wrapper wcqf_stle_<?php echo esc_attr( $layout ); ?>">
		<?php if ( $layout === 'layout1' ) { ?>
			<button type="button" class="qty-minus">-</button>
			<?php
		}
	}

	/**
	 * Add product quantity text and arror buttons
	 *
	 * @return string
	 */
	public function after_quantity_input_field() {
		$api_value       = Fns::get_options();
		$layout          = ! empty( $api_value['qtyLayout'] ) ? sanitize_text_field( $api_value['qtyLayout'] ) : '';
		$quantity_single = ! empty( $api_value['isProductShowQtyField'] ) && (int) $api_value['isProductShowQtyField'] === 1;
		$checkout_field  = ! empty( $api_value['isCheckoutShowQtyField'] ) && (int) $api_value['isCheckoutShowQtyField'] === 1;
		$cart_field      = ! empty( $api_value['isCartShowQtyField'] ) && (int) $api_value['isCartShowQtyField'] === 1;
		if ( ( is_product() && $quantity_single != 1 ) || ( is_checkout() && $checkout_field != 1 ) || ( is_cart() && $cart_field != 1 ) ) {
			return;
		}
		if ( $layout === 'layout1' ) {
			?>
			<button type="button" class="qty-plus">+</button>
		<?php } ?>
		</div>
		<?php
	}

}
