<?php

namespace TinySolutions\wp_quantity_field\Common;

use TinySolutions\wp_quantity_field\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * AssetsController
 */
class Assets {

	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * @var object
	 */
	protected $loader;

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Ajax URL
	 *
	 * @var string
	 */
	private $ajaxurl;

	/**
	 * Class Constructor
	 */

	/**
	 * @return void
	 */
	private function __construct() {
		$this->loader  = Loader::instance();
		$this->version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : WC_QUANTITY_FIELD_VERSION;
		/**
		 * Admin scripts.
		 */
		$this->loader->add_action( 'admin_enqueue_scripts', $this, 'backend_assets', 1 );

        /**
         * Admin scripts.
         */
        $this->loader->add_action( 'wp_enqueue_scripts', $this, 'front_end_assets', 1 );
	}


	/**
	 * Registers Admin scripts.
	 *
	 * @return void
	 */
	public function backend_assets( $hook ) {

		$styles = [
			[
				'handle' => 'ancenter-settings',
				'src'    => wp_quantity_field_main()->get_assets_uri( 'css/backend/admin-settings.css' ),
			],
		];

		// Register public styles.
		foreach ( $styles as $style ) {
			wp_register_style( $style['handle'], $style['src'], '', $this->version );
		}

		$scripts = [
			[
				'handle' => 'ancenter-settings',
				'src'    => wp_quantity_field_main()->get_assets_uri( 'js/backend/admin-settings.js' ),
				'deps'   => [],
				'footer' => true,
			],
		];

		// Register public scripts.
		foreach ( $scripts as $script ) {
			wp_register_script( $script['handle'], $script['src'], $script['deps'], $this->version, $script['footer'] );
		}

		$current_screen = get_current_screen();
		if ( isset( $current_screen->id ) && 'toplevel_page_ancenter-admin' === $current_screen->id ) {
			// Enqueue ThickBox scripts and styles.
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );
			// WPml Create Issue
			wp_dequeue_style( 'wpml-tm-styles' );
			wp_dequeue_script( 'wpml-tm-scripts' );

			wp_enqueue_style( 'ancenter-settings' );
			wp_enqueue_script( 'ancenter-settings' );

			wp_localize_script(
				'ancenter-settings',
				'ancenterParams',
				[
					'ajaxUrl'                   => esc_url( admin_url( 'admin-ajax.php' ) ),
					'adminUrl'                  => esc_url( admin_url() ),
					'restApiUrl'                => esc_url_raw( rest_url() ), // site_url(rest_get_url_prefix()),
					'rest_nonce'                => wp_create_nonce( 'wp_rest' ),
					wp_quantity_field_main()->nonceId => wp_create_nonce( wp_quantity_field_main()->nonceId ),
				]
			);

		}
	}

    /**
     * Registers Front-end Scripts
     * @returns void
    */
    public function front_end_assets( $hook ) {
        $styles = [
            [
                'handle' => 'ancenter-settings',
                'src'    => wp_quantity_field_main()->get_assets_uri( 'css/backend/admin-settings.css' ),
            ],
        ];
    }
}
