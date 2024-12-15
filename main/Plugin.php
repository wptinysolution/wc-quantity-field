<?php
/**
 * Main initialization class.
 *
 * @package TinySolutions\wp_quantity_field
 */

namespace TinySolutions\wp_quantity_field;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use TinySolutions\wp_quantity_field\Common\Loader;
use TinySolutions\wp_quantity_field\Register\Ninjagallery;
use TinySolutions\wp_quantity_field\Traits\SingletonTrait;
use TinySolutions\wp_quantity_field\Admin\Installation;
use TinySolutions\wp_quantity_field\Admin\Dependencies;
use TinySolutions\wp_quantity_field\Common\Assets;
use TinySolutions\wp_quantity_field\Hooks\MainHooks;
use TinySolutions\wp_quantity_field\Admin\AdminMenu;
use TinySolutions\wp_quantity_field\Common\Api;
use TinySolutions\wp_quantity_field\Admin\Review;

/**
 * Main initialization class.
 */
final class Plugin {

	/**
	 * @var object
	 */
	protected $loader;

	/**
	 * Nonce id
	 *
	 * @var string
	 */
	public $nonceId = 'ancenter_wpnonce';

	/**
	 * Post Type.
	 *
	 * @var string
	 */
	public $current_theme;
	/**
	 * Post Type.
	 *
	 * @var string
	 */
	public $category = 'ancenter_category';
	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * Class Constructor
	 */
	private function __construct() {
		$this->loader        = Loader::instance();
		$this->current_theme = wp_get_theme()->get( 'TextDomain' );
		$this->loader->add_action( 'init', $this, 'language' );
		// Register Plugin Active Hook.
		register_activation_hook( WC_QUANTITY_FIELD_FILE, [ Installation::class, 'activate' ] );
		// Register Plugin Deactivate Hook.
		register_deactivation_hook( WC_QUANTITY_FIELD_FILE, [ Installation::class, 'deactivation' ] );
		$this->run();
	}


	/**
	 * Load Text Domain
	 */
	public function plugins_loaded() {
	}

	/**
	 * Assets url generate with given assets file
	 *
	 * @param string $file File.
	 *
	 * @return string
	 */
	public function get_assets_uri( $file ) {
		$file = ltrim( $file, '/' );
		return trailingslashit( WC_QUANTITY_FIELD_URL . '/assets' ) . $file;
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public function get_template_path() {
		return apply_filters( 'ancenter_template_path', 'templates/' );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( WC_QUANTITY_FIELD_FILE ) );
	}

	/**
	 * Load Text Domain
	 */
	public function language() {
		load_plugin_textdomain( 'ancenter', false, WC_QUANTITY_FIELD_ABSPATH . '/languages/' );
	}

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {
		do_action( 'ancenter/before_init' );
		Ninjagallery::instance();
		Review::instance();
		// Include File.
		Assets::instance();
		AdminMenu::instance();
		MainHooks::instance();
		Api::instance();
		do_action( 'ancenter/after_init' );
	}

	/**
	 * Checks if Pro version installed
	 *
	 * @return boolean
	 */
	public function has_pro() {
		return function_exists( 'ancenterp' );
	}

	/**
	 * PRO Version URL.
	 *
	 * @return string
	 */
	public function pro_version_link() {
		return '#';
	}

	/**
	 * @return void
	 */
	private function run() {
		if ( Dependencies::instance()->check() ) {
            $this->init();
			do_action( 'ancenter/after_run' );
		}
		$this->loader->run();
	}
}
