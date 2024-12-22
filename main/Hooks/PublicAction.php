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
        $this->loader->add_action('woocommerce_before_add_to_cart_form', $this, 'before_sinlge_quantity_wrapper_div' );
        $this->loader->add_action('woocommerce_after_add_to_cart_form', $this, 'after_sinlge_quantity_wrapper_div' );
		$this->loader->add_action('woocommerce_before_quantity_input_field', $this, 'before_quantity_input_field' );
		$this->loader->add_action('woocommerce_after_quantity_input_field', $this, 'after_quantity_input_field' );
    }

    /**
     * Add product single page before wrapper div
     * @return string
     */
    public function before_sinlge_quantity_wrapper_div(){
        $api_value = Fns::get_options();
        $layout = !empty( $api_value['qtyLayout'] ) ? sanitize_text_field( $api_value['qtyLayout'] ) : '';
        ?>
        <div class="wcqf-quantity-wrapper wcqf_<?php echo esc_attr( $layout );?>">
    <?php }

    /**
     * Add product single page after wrapper div
     * @return string
     */
    public function after_sinlge_quantity_wrapper_div(){ ?>
        </div>
    <?php }

    /**
     * Add product quantity text and arror buttons
     * @return string
     */
    public function before_quantity_input_field() {
        $api_value = Fns::get_options();
        $layout = !empty( $api_value['qtyLayout'] ) ? sanitize_text_field( $api_value['qtyLayout'] ) : '';
        $quantity_text = !empty($api_value['qtyText']) ? sanitize_text_field($api_value['qtyText']) : esc_html__('Quantity', 'wc-quantity-field');
        $quantity_field = !empty($api_value['isShopShowQtyText']) && (int)$api_value['isShopShowQtyText'] === 1;
        if( $quantity_field ){ ?>
            <div class="wcqf-quantity-text ">
                <?php echo esc_html( $quantity_text );?>
            </div>
        <?php } ?>
        <div class="wcqf-btn-wrapper">
        <?php if( $layout === 'layout1' ){ ?>
            <button type="button" class="qty-minus">-</button>
        <?php }
    }

    /**
     * Add product quantity text and arror buttons
     * @return string
     */
	public function after_quantity_input_field() {
        $api_value = Fns::get_options();
        $layout = !empty( $api_value['qtyLayout'] ) ? sanitize_text_field( $api_value['qtyLayout'] ) : '';
        if( $layout === 'layout1' ){ ?>
            <button type="button" class="qty-plus">+</button>
        <?php } ?>
        </div>
    <?php }
}
