<?php

namespace TinySolutions\wcqf\Common;

use TinySolutions\wcqf\Helpers\Fns;
use TinySolutions\wcqf\Traits\SingletonTrait;
use WP_Error;

/**
 * API Class
 */
class Api {

	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * @var object
	 */
	protected $loader;

	/**
	 * @var string
	 */
	private $namespacev1 = 'TinySolutions/wcqf/v1';

	/**TinySolutions/wcqf/v1/updateOptions
	 * @var string
	 */
	private $resource_name = '/api';

	/**
	 * Construct
	 */
	private function __construct() {
		$this->loader = Loader::instance();
		$this->loader->add_action( 'rest_api_init', $this, 'register_routes' );
	}

	/**
	 * Register our routes.
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespacev1,
			$this->resource_name . '/getOptions',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_options' ],
				'permission_callback' => [ $this, 'login_permission_callback' ],
			]
		);
		register_rest_route(
			$this->namespacev1,
			$this->resource_name . '/updateOptions',
			[
				'methods'             => 'POST',
				'callback'            => [ $this, 'update_option' ],
				'permission_callback' => [ $this, 'login_permission_callback' ],
			]
		);
		
		register_rest_route(
			$this->namespacev1,
			$this->resource_name . '/getPluginList',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_plugin_list' ],
				'permission_callback' => [ $this, 'login_permission_callback' ],
			]
		);
	}

	/**
	 * @return true
	 */
	public function login_permission_callback() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * @return false|string
	 */
	public function get_plugin_list() {
		// Define a unique key for the transient.
		$transient_key = 'get_plugin_list_use_cache_' . WC_QUANTITY_FIELD_VERSION;
		// Try to get the cached data.
		$cached_data = get_transient( $transient_key );
		if ( ! empty( $cached_data ) ) {
			$is_empty = json_decode( $cached_data );
			// Return the cached data if it exists.
			if ( ! empty( $is_empty ) ) {
				return $cached_data;
			}
		}
		// Initialize the result array.
		$result = [];
		try {
			// Fetch data from the API.
			$response = wp_remote_get( 'https://api.wordpress.org/plugins/info/1.2/?action=query_plugins&request[author]=tinysolution' );
			if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
				$responseBody = json_decode( $response['body'], true );
				if ( json_last_error() === JSON_ERROR_NONE && is_array( $responseBody['plugins'] ) ) {
					foreach ( $responseBody['plugins'] as $plugin ) {
						$result[] = [
							'plugin_name'       => $plugin['name'],
							'slug'              => $plugin['slug'],
							'author'            => $plugin['author'],
							'homepage'          => $plugin['homepage'],
							'download_link'     => $plugin['download_link'],
							'author_profile'    => $plugin['author_profile'],
							'icons'             => $plugin['icons'],
							'short_description' => $plugin['short_description'],
							'TB_iframe'         => esc_url( self_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . $plugin['slug'] . '&TB_iframe=true&width=772&height=700' ) ),
						];
					}
				}
			}
		} catch ( \Exception $ex ) {
			// Handle exception (optional logging or error handling can be added here).
		}

		// Encode the result to JSON.
		$json_result = wp_json_encode( $result );

		// Cache the result for 1 day (24 hours * 60 minutes * 60 seconds).
		set_transient( $transient_key, $json_result, 7 * DAY_IN_SECONDS );

		return $json_result;
	}
	
	/**
	 * @return array
	 */
	public function update_option( $request_data ) {
		$result = [
			'message' => esc_html__( 'Update failed. Maybe change not found. ', 'textdomain' ),
		];
		$parameters = $request_data->get_params();
		$the_settings = get_option( 'wcqf_settings', [] );
		$the_settings['qtyText'] = sanitize_text_field($parameters['qtyText'] ?? '' );
		$the_settings['qtyLayout'] = sanitize_text_field($parameters['qtyLayout'] ?? '' );
		$the_settings['isShopShowQtyText'] = boolval($parameters['isShopShowQtyText'] ?? false );
		$the_settings['isShopShowQtyField'] = boolval($parameters['isShopShowQtyField'] ?? false );
		$the_settings['isCheckoutShowQtyField'] = boolval($parameters['isCheckoutShowQtyField'] ?? false );
		$the_settings['isProductShowQtyField'] = boolval($parameters['isProductShowQtyField'] ?? false );
		$the_settings['isCartShowQtyField'] = boolval($parameters['isCartShowQtyField'] ?? false );
		$options = update_option( 'wcqf_settings', $the_settings );
		$result['updated'] = boolval( $options );
		if ( $result['updated'] ) {
			$result['message'] = esc_html__( 'Updated.', 'wc-quantity-field' );
		}
		return $result;
	}



	/**
	 * @return false|string
	 */
	public function get_options() {
		return wp_json_encode( Fns::get_options() );
	}
}
