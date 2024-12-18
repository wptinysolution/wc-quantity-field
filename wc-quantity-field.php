<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Quantity Field For Woocommerce ( Masud )
 * Plugin URI:        https://wordpress.org/plugins/admin-notice-centralization
 * Description:       Boiler
 * Version:           0.0.2
 * Author:            Tiny Solutions
 * Author URI:        https://www.wptinysolutions.com/
 * Text Domain:       wc-quantity-field
 * Domain Path:       /languages
 * License:   GPLv3
 * License URI:                http://www.gnu.org/licenses/gpl-3.0.html
 * @package TinySolutions\mlt
 */

// Do not allow directly accessing this the file.

use TinySolutions\wp_quantity_field\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Define media edit Constant.
 */
define( 'WC_QUANTITY_FIELD_VERSION', '0.0.1' );

define( 'WC_QUANTITY_FIELD_FILE', __FILE__ );

define( 'WC_QUANTITY_FIELD_BASENAME', plugin_basename( WC_QUANTITY_FIELD_FILE ) );

define( 'WC_QUANTITY_FIELD_URL', plugins_url( '', WC_QUANTITY_FIELD_FILE ) );

define( 'WC_QUANTITY_FIELD_ABSPATH', dirname( WC_QUANTITY_FIELD_FILE ) );

define( 'WC_QUANTITY_FIELD_PATH', plugin_dir_path( __FILE__ ) );

/**
 * App Init.
 */

require_once WC_QUANTITY_FIELD_PATH . 'vendor/autoload.php';

/**
 * @return Plugin
 */
function wp_quantity_field_main() {
	return Plugin::instance();
}

add_action( 'plugins_loaded', 'wp_quantity_field_main' );
