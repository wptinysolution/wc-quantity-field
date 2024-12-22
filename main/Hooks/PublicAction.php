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
		$this->loader->add_action('woocommerce_before_quantity_input_field', $this, 'before_quantity_input_field' );
		$this->loader->add_action('woocommerce_after_quantity_input_field', $this, 'after_quantity_input_field' );
        //add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'add_quantity_field_single_page' ], 20);

        //add_action('woocommerce_before_single_product', [ $this, 'remove_single_product_quantity_field' ]);
    }

    public function before_quantity_input_field() {
        ?>
        <div class="quantity-buttons">
            <button type="button" class="qty-minus">-</button>
		<?php
    }
	
	public function after_quantity_input_field() {
		?>
        <button type="button" class="qty-plus">+</button>
		</div>
		<?php
	}

    private function render_quantity_field($html, $product) {
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

        $custom_file = $html;
        if ($api_value['qtyLayout'] === 'layout1') {
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

            $quantity_text = !empty($api_value['qtyText']) ? sanitize_text_field($api_value['qtyText']) : esc_html__('Quantity', 'wc-quantity-field');
            $quantity_field = !empty($api_value['isShopShowQtyText']) && (int)$api_value['isShopShowQtyText'] === 1;

            if ($quantity_field) {
                $custom_file .= '<style>
                .wcqf-quanity-text .quantity:before {
                  content:"' . esc_html($quantity_text) . ':";
                }
            </style>';
            }
        }
        return $custom_file;
    }

 


    /**
     * Add quantity field for product single page
     *
     * @param string $html
     * @param WC_Product $product
     * @return string
     */
//    public function add_quantity_field_single_page() {
//        global $product;
//        if (!$product || !$product->is_type('simple') || !$product->is_purchasable() || !$product->is_in_stock() || $product->is_sold_individually()) {
//            return;
//        }
//        $html = '';
//        $html = $this->render_quantity_field($html, $product);
//        echo $html;
//    }
}
