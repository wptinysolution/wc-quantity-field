<?php

namespace TinySolutions\wp_quantity_field\Admin;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

class Installation {
	/**
	 * @return void
	 */
	public static function activate() {
		if ( ! get_option( 'wcqf_plugin_version' ) ) {
			$options             = get_option( 'wcqf_settings', [] );
			$get_activation_time = strtotime( 'now' );

			update_option( 'wcqf_settings', $options );
			update_option( 'wcqf_plugin_version', WC_QUANTITY_FIELD_VERSION );
			update_option( 'wcqf_plugin_activation_time', $get_activation_time );
		}
	}

	/**
	 * @return void
	 */
	public static function deactivation() { }
}
