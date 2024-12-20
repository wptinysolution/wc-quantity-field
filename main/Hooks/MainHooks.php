<?php
/**
 * Main ActionHooks class.
 *
 * @package TinySolutions\wcqf
 */

namespace TinySolutions\wcqf\Hooks;

use TinySolutions\wcqf\Admin\Upgrade;
use TinySolutions\wcqf\Traits\SingletonTrait;

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
