<?php
/*
 * Plugin Name: Quantity field for woocommerce
 * Plugin URl: https://test.com
 * Version: 1.0.0
 * Author Name: Mrana
 * Author URL: https://softcoders.net/
 * */

if ( !class_exists( 'quantity_field_for_foocommerce' ) ){
    class quantity_field_for_foocommerce {
        /**
         * Single instance of the class
         * @ver quantity_field_for_foocommerce the single instance of the class
         */
        private static $instance = null;

        /**
         * Ensures only one instance of quantity_field_for_foocommerce is loaded or can be loaded.
         */
        public static function instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        public function __construct() {
            //add_action( 'init', [ $this, 'sc_jquery_sourch' ] );
            add_action( 'wp_enqueue_scripts', [ $this, 'sc_jquery_sourch' ] );
            add_action( 'woocommerce_loop_add_to_cart_link', [ $this, 'add_quantity_field' ], 20, 2 );
            add_action( 'woocommerce_quantity_input_args', [ $this, 'sc_quantity_input_args' ], 20, 2 );
        }
        /**
         * add quantity field
         * return $html
         * */
        public function add_quantity_field( $html, $product  ) {
            error_log( print_r( $html , true) . "\n\n", 3, __DIR__ . '/log.txt' );
            if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
                $custom_file = '<div class="sc-quantity-wrapper">';
                $custom_file .= woocommerce_quantity_input( array(), $product, false );
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
            if( is_singular( 'product' ) && $product->is_type( 'simple') && $product->is_purchasable() & $product->is_in_stock() && ! $product->is_sold_individually() ){
                $args['input_value'] 	= 1;
            }
            $args['max_value'] = $product->get_stock_quantity();
            $args['min_value'] = 0;
            $args['step'] = 1;
            return $args;
        }

       /**
        * @return void
        */

        public function sc_jquery_sourch() {
            wp_enqueue_style( 'sc-main-css', plugin_dir_url( __FILE__ ) . 'assets/css/main.css' );
            wp_enqueue_script( 'sc-main-js', plugin_dir_url( __FILE__ ) . 'assets/js/main.js', ['jquery'], '1.0.0', true );
        }

    }
}

function quantity_field_for_woocommerce_return() {
    return quantity_field_for_foocommerce::instance();
}
add_action( 'plugins_loaded', 'quantity_field_for_woocommerce_return');