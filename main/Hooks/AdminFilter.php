<?php
/**
 * Main FilterHooks class.
 *
 * @package TinySolutions\WM
 */

namespace TinySolutions\wcqf\Hooks;

use TinySolutions\wcqf\Common\Loader;
use TinySolutions\wcqf\Helpers\Fns;
use TinySolutions\wcqf\Traits\SingletonTrait;

defined( 'ABSPATH' ) || exit();

/**
 * Main FilterHooks class.
 */
class AdminFilter {

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
		// Plugins Setting Page.
		$this->loader->add_filter( 'plugin_action_links_' . WC_QUANTITY_FIELD_BASENAME, $this, 'plugins_setting_links' );
		$this->loader->add_filter( 'plugin_row_meta', $this, 'plugin_row_meta', 10, 2 );
	}

	/**
	 * @param array $links default plugin action link
	 *
	 * @return array [array] plugin action link
	 */
	public function plugins_setting_links( $links ) {
		$links['wcqf_settings'] = '<a href="' . admin_url( 'admin.php?page=wcqf-admin' ) . '">' . esc_html__( 'Settings', 'wc-quantity-field' ) . '</a>';
		return $links;
	}
	/**
	 * @param $links
	 * @param $file
	 *
	 * @return array
	 */
	public function plugin_row_meta( $links, $file ) {
		if ( WC_QUANTITY_FIELD_BASENAME === $file ) {
			$report_url         = 'https://www.wptinysolutions.com/contact';
			$row_meta['issues'] = sprintf( '%2$s <a target="_blank" href="%1$s">%3$s</a>', esc_url( $report_url ), esc_html__( 'Facing issue?', 'wc-quantity-field' ), '<span style="color: red">' . esc_html__( 'Please open a support ticket.', 'wc-quantity-field' ) . '</span>' );
			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
}
