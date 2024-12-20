<?php
/**
 * Fns Helpers class
 *
 * @package  TinySolutions\wcqf
 */

namespace TinySolutions\wcqf\Helpers;

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
		$nonce = isset( $_REQUEST[ wcqf()->nonceId ] ) ? $_REQUEST[ wcqf()->nonceId ] : null;
		if ( wp_verify_nonce( $nonce, wcqf()->nonceId ) ) {
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
