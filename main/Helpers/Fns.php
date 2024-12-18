<?php
/**
 * Fns Helpers class
 *
 * @package  TinySolutions\wp_quantity_field
 */

namespace TinySolutions\wp_quantity_field\Helpers;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Fns class
 */
class Fns {

	/**
	 *  Verify nonce.
	 *
	 * @return bool
	 */
	public static function verify_nonce() {
		$nonce = isset( $_REQUEST[ wp_quantity_field_main()->nonceId ] ) ? $_REQUEST[ wp_quantity_field_main()->nonceId ] : null;
		if ( wp_verify_nonce( $nonce, wp_quantity_field_main()->nonceId ) ) {
			return true;
		}
		return false;
	}

	/**
	 * @param $plugin_file_path
	 *
	 * @return bool
	 */
	public static function is_plugins_installed( $plugin_file_path = null ) {
		$installed_plugins_list = get_plugins();
		return isset( $installed_plugins_list[ $plugin_file_path ] );
	}


	/**
	 * @return array
	 */
	public static function get_options() {
		$defaults = [];
		$options  = get_option( 'wcqf_settings' );
		return wp_parse_args( $options, $defaults );
	}

	/**
	 * Beautify string
	 *
	 * @param [type] $string
	 * @return string
	 */
	public static function beautify( $string ) {
		return ucwords( str_replace( '_', ' ', $string ) );
	}
	/**
	 * Beautify string
	 *
	 * @param [type] $string
	 * @return $string
	 */
	public static function uglify( $string ) {
		return strtolower( str_replace( ' ', '_', $string ) );
	}

	/**
	 * Pluralize String
	 *
	 * @param [type] $string
	 * @return $Plural_string
	 */
	public static function pluralize( $string = '' ) {
		$last = $string[ strlen( $string ) - 1 ];
		if ( 'y' === $last ) {
			$cut = substr( $string, 0, -1 );
			// convert y to ies.
			$plural = $cut . 'ies';
		} else {
			// just attach an s.
			$plural = $string . 's';
		}
		return $plural;
	}
}
