<?php
/**
 * Main Upgrade class.
 *
 * @package RadiusTheme\SB
 */

namespace TinySolutions\wcqf\Admin;

use TinySolutions\wcqf\Common\Loader;

use TinySolutions\wcqf\Traits\SingletonTrait;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Main Upgrade class.
 */
class Upgrade {
	/**
	 * Singleton Trait.
	 */
	use SingletonTrait;

	/**
	 * @var object
	 */
	protected $loader;

	/**
	 * Class Constructor.
	 */
	private function __construct() {
		$this->loader = Loader::instance();
		$this->loader->add_action( 'admin_head', $this, 'upgrade_styles' );
		$this->loader->add_action( 'in_plugin_update_message-' . WC_QUANTITY_FIELD_BASENAME, $this, 'version_update_warning' );
	}

	/**
	 * Update message
	 *
	 * @param int $new_version New Version.
	 *
	 * @return void
	 */
	public function version_update_warning( $plugin_data ) {
		$new_version           = $plugin_data['new_version'];
		$current_version_crit  = explode( '.', WC_QUANTITY_FIELD_VERSION )[0];
		$new_version_crit      = explode( '.', $new_version )[0];
		$current_version_major = explode( '.', WC_QUANTITY_FIELD_VERSION )[1];
		$new_version_major     = explode( '.', $new_version )[1];

		if ( $current_version_crit === $new_version_crit ) {
			if ( $current_version_major === $new_version_major ) {
				return;
			}
		}
		?>
		<div class="wcqf-major-update-warning">
			<div class="wcqf-major-update-icon">
				<i class="dashicons dashicons-info"></i>
			</div>
			<div>
				<div class="wcqf-major-update-title">
					<?php
					printf(
						'%s%s.',
						esc_html__( 'Heads up, Please backup before upgrade to version ', 'wc-quantity-field' ),
						esc_html( $new_version )
					);
					?>
				</div>
				<div class="wcqf-major-update-message">
					The latest update includes some substantial changes across different areas of the plugin. <br/>We
					highly recommend you to <b>backup your site before upgrading</b>, and make sure you first update in a staging environment.
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Admin styles.
	 *
	 * @return void
	 */
	public function upgrade_styles() {
		global $pagenow;

		if ( 'plugins.php' !== $pagenow ) {
			return;
		}

		echo '<style>
			.wcqf-major-update-warning {
				border-top: 2px solid #d63638;
				padding-top: 15px;
				margin-top: 15px;
				margin-bottom: 15px;
				display: flex;
			}

			.wcqf-major-update-icon i {
				color: #d63638;
				margin-right: 8px;
			}

			.wcqf-major-update-warning + p {
				display: none;
			}

			.wcqf-major-update-title {
				font-weight: 600;
				margin-bottom: 10px;
			}

			.notice-success .wcqf-major-update-warning {
				border-color: #46b450;
			}

			.notice-success .wcqf-major-update-icon i {
				color: #79ba49;
			}
		</style>';
	}
}
