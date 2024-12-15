<?php
/**
 * Main ActionHooks class.
 *
 * @package TinySolutions\wp_quantity_field
 */

namespace TinySolutions\wp_quantity_field\Hooks;

use TinySolutions\wp_quantity_field\Common\Loader;
use TinySolutions\wp_quantity_field\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Main ActionHooks class.
 */
class AdminAction {

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
