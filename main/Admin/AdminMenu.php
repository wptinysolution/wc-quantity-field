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
		
		add_submenu_page(
			self::MENU_PAGE_SLUG,
			esc_html__( 'Quantity Settings', 'wc-quantity-field' ),
			'<span class="wcqf-is-submenu" ><span class="dashicons dashicons-arrow-right-alt" ></span>' . esc_html__( 'Quantity Settings', 'wc-quantity-field' ) . '</span>',
			self::MENU_CAPABILITY,
			self::MENU_PAGE_SLUG,
		);
		$menu_link_part = admin_url( 'admin.php?page=wcqf-admin' );

		add_submenu_page(
			self::MENU_PAGE_SLUG,
			esc_html__( 'Useful Plugins', 'wc-quantity-field' ),
			'<span class="wcqf-is-submenu" ><span class="dashicons dashicons-arrow-right-alt" ></span>' . esc_html__( 'Useful Plugins', 'wc-quantity-field' ) . '</span>',
			self::MENU_CAPABILITY,
			$menu_link_part . '#/plugins'
		);
		add_submenu_page(
			self::MENU_PAGE_SLUG,
			esc_html__( 'Contacts Support', 'wc-quantity-field' ),
			'<span class="wcqf-is-submenu" ><span class="dashicons dashicons-arrow-right-alt" ></span>' . esc_html__( 'Contacts Support', 'wc-quantity-field' ) . '</span>',
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
		echo '<div class="wrap"><div id="wcqf_root"></div></div>';
	}
}
