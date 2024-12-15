<?php
/**
 * Main ActionHooks class.
 *
 * @package TinySolutions\wp_quantity_field
 */

namespace TinySolutions\wp_quantity_field\Hooks;

use TinySolutions\wp_quantity_field\Admin\Upgrade;
use TinySolutions\wp_quantity_field\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Main ActionHooks class.
 */
class MainHooks {
	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * Init Hooks.
	 *
	 * @return void
	 */
	private function __construct() {
		if ( is_admin() ) {
			AdminAction::instance();
			AdminFilter::instance();
			Upgrade::instance();
		} else {
			PublicAction::instance();
			PublicFilter::instance();
		}
		Ajax::instance();
	}
}
