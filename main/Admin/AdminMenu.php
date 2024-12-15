<?php

namespace TinySolutions\wp_quantity_field\Admin;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

use TinySolutions\wp_quantity_field\Common\Loader;
use TinySolutions\wp_quantity_field\Traits\SingletonTrait;

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
	const MENU_PAGE_SLUG = 'ancenter-admin';
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
			'Admin Notice',
			'Admin Notice',
			'manage_options',
			'ancenter-admin',
			[ $this, 'module_page_callback' ],
			'dashicons-screenoptions',
			57
		);
		add_submenu_page(
			self::MENU_PAGE_SLUG,
			esc_html__( 'Admin Notice', 'ancenter' ),
			'<span class="ancenter-is-submenu" ><span class="dashicons dashicons-arrow-right-alt" ></span>' . esc_html__( 'Admin Notice', 'ancenter' ) . '</span>',
			self::MENU_CAPABILITY,
			self::MENU_PAGE_SLUG,
		);
		$menu_link_part = admin_url( 'admin.php?page=ancenter-admin' );

		add_submenu_page(
			self::MENU_PAGE_SLUG,
			esc_html__( 'Useful Plugins', 'ancenter' ),
			'<span class="ancenter-is-submenu" ><span class="dashicons dashicons-arrow-right-alt" ></span>' . esc_html__( 'Useful Plugins', 'ancenter' ) . '</span>',
			self::MENU_CAPABILITY,
			$menu_link_part . '#/plugins'
		);
		add_submenu_page(
			self::MENU_PAGE_SLUG,
			esc_html__( 'Contacts Support', 'ancenter' ),
			'<span class="ancenter-is-submenu" ><span class="dashicons dashicons-arrow-right-alt" ></span>' . esc_html__( 'Contacts Support', 'ancenter' ) . '</span>',
			self::MENU_CAPABILITY,
			$menu_link_part . '#/support'
		);
	}

	/**
	 * Render submenu
	 *
	 * @return void
	 */
	public function module_page_callback() {
		echo '<div class="wrap"><div id="ancenter_root"></div></div>';
	}
}
