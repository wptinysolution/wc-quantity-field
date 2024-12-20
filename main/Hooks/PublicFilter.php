<?php
/**
 * Main ActionHooks class.
 *
 * @package TinySolutions\wcqf
 */

namespace TinySolutions\wcqf\Hooks;

use TinySolutions\wcqf\Common\Loader;
use TinySolutions\wcqf\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Main ActionHooks class.
 */
class PublicFilter {
	/**
	 * @var object
	 */
	protected $loader;
	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * Class Constructor
	 */
	private function __construct() {
		$this->loader = Loader::instance();
	}
}
