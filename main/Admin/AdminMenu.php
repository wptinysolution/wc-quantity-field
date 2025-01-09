<?php

namespace TinySolutions\wcqf\Admin;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use TinySolutions\wcqf\Common\Loader;
use TinySolutions\wcqf\Traits\SingletonTrait;

/**
 * Sub menu class
 *
 * @author Mostafa <mostafa.soufi@hotmail.com>
 */
class AdminMenu {
	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * @var object
	 */
	protected $loader;

	/**
	 * Parent Menu Page Slug
	 */
	const MENU_PAGE_SLUG = 'wcqf-admin';
	/**
	 * Menu capability
	 */
	const MENU_CAPABILITY = 'manage_options';

	/**
	 * Autoload method
	 *
	 * @return void
	 */
	private function __construct() {
		$this->loader = Loader::instance();
		$this->loader->add_action( 'admin_menu', $this, 'register_sub_menu' );
	}

	/**
	 * Register submenu
	 *
	 * @return void
	 */
	public function register_sub_menu() {
		add_menu_page(
			'Quantity Settings',
			'Quantity Settings',
			'manage_options',
			'wcqf-admin',
			[ $this, 'module_page_callback' ],
			'dashicons-screenoptions',
			57
		);
	}

	/**
	 * Render submenu
	 *
	 * @return void
	 */
	public function module_page_callback() {
		echo '<div class="wrap"><div id="wcqf_root"></div></div>';
	}
}
