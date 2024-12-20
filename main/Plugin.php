<?php
/**
 * Main initialization class.
 *
 * @package TinySolutions\wcqf
 */

namespace TinySolutions\wcqf;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use TinySolutions\wcqf\Common\Loader;
use TinySolutions\wcqf\Register\Ninjagallery;
use TinySolutions\wcqf\Traits\SingletonTrait;
use TinySolutions\wcqf\Admin\Installation;
use TinySolutions\wcqf\Admin\Dependencies;
use TinySolutions\wcqf\Common\Assets;
use TinySolutions\wcqf\Hooks\MainHooks;
use TinySolutions\wcqf\Admin\AdminMenu;
use TinySolutions\wcqf\Common\Api;
use TinySolutions\wcqf\Admin\Review;

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
	public $nonceId = 'wcqf_wpnonce';

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
	public $category = 'wcqf_category';
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
		return apply_filters( 'wcqf_template_path', 'templates/' );
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
		load_plugin_textdomain( 'wc-quantity-field', false, WC_QUANTITY_FIELD_ABSPATH . '/languages/' );
	}

	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {
		do_action( 'wcqf/before_init' );
		Ninjagallery::instance();
		Review::instance();
		// Include File.
		Assets::instance();
		AdminMenu::instance();
		MainHooks::instance();
		Api::instance();
		do_action( 'wcqf/after_init' );
	}

	/**
	 * Checks if Pro version installed
	 *
	 * @return boolean
	 */
	public function has_pro() {
		return function_exists( 'wcqfp' );
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
			do_action( 'wcqf/after_run' );
		}
		$this->loader->run();
	}
}
