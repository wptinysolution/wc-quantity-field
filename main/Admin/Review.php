<?php

namespace TinySolutions\wcqf\Admin;

use TinySolutions\wcqf\Common\Loader;
use TinySolutions\wcqf\Traits\SingletonTrait;

/**
 * Review class
 */
class Review {
	/**
	 * @var object
	 */
	protected $loader;

	/**
	 * Singleton
	 */
	use SingletonTrait;

	/**
	 * Template builder post type
	 *
	 * @var string
	 */
	public string $textdomain = 'wc-quantity-field';

	/**
	 * Init
	 *
	 * @return void
	 */
	private function __construct() {
		$this->loader = Loader::instance();
		$this->loader->add_action( 'admin_init', $this, 'wcqf_check_installation_time' );
		$this->loader->add_action( 'admin_init', $this, 'wcqf_spare_me', 5 );
		if ( true ) {
			$this->loader->add_action( 'admin_footer', $this, 'deactivation_popup', 99 ); // Remove the hooks if no longer need.
		}
	}

	/**
	 * Check if review notice should be shown or not
	 *
	 * @return void
	 */
	public function wcqf_check_installation_time() {

		// Added Lines Start
		$nobug = get_option( 'wcqf_spare_me' );
		$rated = get_option( 'wcqf_rated' );
		if ( '1' == $nobug || 'yes' == $rated ) {
			return;
		}
		$now = strtotime( 'now' );
		$install_date = get_option( 'wcqf_plugin_activation_time' );
		$past_date = strtotime( '+10 days', $install_date );
		$remind_time = get_option( 'wcqf_remind_me' );

		if ( ! $remind_time ) {
			$remind_time = $install_date;
		}

		$remind_time = $remind_time ? $remind_time : $past_date;
		$remind_due = strtotime( '+10 days', $remind_time );
		if ( ! $now > $past_date || $now < $remind_due ) {
			return;
		}
		$this->loader->add_action( 'admin_notices', $this, 'wcqf_display_admin_notice' );
	}

	/**
	 * Remove the notice for the user if review already done or if the user does not want to
	 *
	 * @return void
	 */
	public function wcqf_spare_me() {

		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wcqf_notice_nonce' ) ) {
			return;
		}
		if ( isset( $_GET['wcqf_spare_me'] ) && ! empty( $_GET['wcqf_spare_me'] ) ) {
			$spare_me = absint( $_GET['wcqf_spare_me'] );
			if ( 1 == $spare_me ) {
				update_option( 'wcqf_spare_me', '1' );
			}
		}
		if ( isset( $_GET['wcqf_remind_me'] ) && ! empty( $_GET['wcqf_remind_me'] ) ) {
			$remind_me = absint( $_GET['wcqf_remind_me'] );
			if ( 1 == $remind_me ) {
				$get_activation_time = strtotime( 'now' );
				update_option( 'wcqf_remind_me', $get_activation_time );
			}
		}
		if ( isset( $_GET['wcqf_rated'] ) && ! empty( $_GET['wcqf_rated'] ) ) {
			$wcqf_rated = absint( $_GET['wcqf_rated'] );
			if ( 1 == $wcqf_rated ) {
				update_option( 'wcqf_rated', 'yes' );
			}
		}
	}

	protected function wcqf_current_admin_url() {
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$uri = preg_replace( '|^.*/wp-admin/|i', '', $uri );
		if ( ! $uri ) {
			return '';
		}
		return remove_query_arg(
			[
				'_wpnonce',
				'_wc_notice_nonce',
				'wc_db_update',
				'wc_db_update_nonce',
				'wc-hide-notice',
				'wcqf_spare_me',
				'wcqf_remind_me',
				'wcqf_rated',
			],
			admin_url( $uri )
		);
	}

	/**
	 * Display Admin Notice, asking for a review
	 **/
	public function wcqf_display_admin_notice() {
		// WordPress global variable
		global $pagenow;
		$exclude = [
			'themes.php',
			'users.php',
			'tools.php',
			'options-general.php',
			'options-writing.php',
			'options-reading.php',
			'options-discussion.php',
			'options-media.php',
			'options-permalink.php',
			'options-privacy.php',
			'admin.php',
			'import.php',
			'export.php',
			'site-health.php',
			'export-personal-data.php',
			'erase-personal-data.php',
		];

		if ( ! in_array( $pagenow, $exclude ) ) {
			$args = [ '_wpnonce' => wp_create_nonce( 'wcqf_notice_nonce' ) ];
			$dont_disturb = add_query_arg( $args + [ 'wcqf_spare_me' => '1' ], $this->wcqf_current_admin_url() );
			$remind_me    = add_query_arg( $args + [ 'wcqf_remind_me' => '1' ], $this->wcqf_current_admin_url() );
			$rated        = add_query_arg( $args + [ 'wcqf_rated' => '1' ], $this->wcqf_current_admin_url() );
			$reviewurl    = 'https://wordpress.org/support/plugin/wcqf/reviews/?filter=5#new-post';
			$plugin_name  = 'Modules For Woocommerce';
			?>
			<div class="notice wcqf-review-notice wcqf-review-notice--extended">
				<div class="wcqf-review-notice_content">
					<h3>Enjoying "<?php echo esc_html( $plugin_name ); ?>"? </h3>
					<p>Thank you for choosing "<string><?php echo esc_html( $plugin_name ); ?></string>". If you found our plugin useful, please consider giving us a 5-star rating on WordPress.org. Your feedback will motivate us to grow.</p>
					<div class="wcqf-review-notice_actions">
						<a href="<?php echo esc_url( $reviewurl ); ?>" class="wcqf-review-button wcqf-review-button--cta" target="_blank"><span>⭐ Yes, You Deserve It!</span></a>
						<a href="<?php echo esc_url( $rated ); ?>" class="wcqf-review-button wcqf-review-button--cta wcqf-review-button--outline"><span>😀 Already Rated!</span></a>
						<a href="<?php echo esc_url( $remind_me ); ?>" class="wcqf-review-button wcqf-review-button--cta wcqf-review-button--outline"><span>🔔 Remind Me Later</span></a>
						<!-- <a href="--><?php // echo esc_url( $dont_disturb ); ?><!--" class="wcqf-review-button wcqf-review-button--cta wcqf-review-button--error wcqf-review-button--outline"><span>😐 No Thanks </span></a>-->
					</div>
				</div>
			</div>
			<style>
				.wcqf-review-button--cta {
					--e-button-context-color: #5d3dfd;
					--e-button-context-color-dark: #5d3dfd;
					--e-button-context-tint: rgb(75 47 157/4%);
					--e-focus-color: rgb(75 47 157/40%);
				}

				.wcqf-review-notice {
					position: relative;
					margin: 5px 20px 5px 2px;
					border: 1px solid #ccd0d4;
					background: #fff;
					box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
					font-family: Roboto, Arial, Helvetica, Verdana, sans-serif;
					border-inline-start-width: 4px;
				}

				.wcqf-review-notice.notice {
					padding: 0;
				}

				.wcqf-review-notice:before {
					position: absolute;
					top: -1px;
					bottom: -1px;
					left: -4px;
					display: block;
					width: 4px;
					background: -webkit-linear-gradient(bottom, #5d3dfd 0%, #6939c6 100%);
					background: linear-gradient(0deg, #5d3dfd 0%, #6939c6 100%);
					content: "";
				}

				.wcqf-review-notice_content {
					padding: 20px;
				}

				.wcqf-review-notice_actions > * + * {
					margin-inline-start: 8px;
					-webkit-margin-start: 8px;
					-moz-margin-start: 8px;
				}

				.wcqf-review-notice p {
					margin: 0;
					padding: 0;
					line-height: 1.5;
				}

				p + .wcqf-review-notice_actions {
					margin-top: 1rem;
				}

				.wcqf-review-notice h3 {
					margin: 0;
					font-size: 1.0625rem;
					line-height: 1.2;
				}

				.wcqf-review-notice h3 + p {
					margin-top: 8px;
				}

				.wcqf-review-button {
					display: inline-block;
					padding: 0.4375rem 0.75rem;
					border: 0;
					border-radius: 3px;;
					background: var(--e-button-context-color);
					color: #fff;
					vertical-align: middle;
					text-align: center;
					text-decoration: none;
					white-space: nowrap;
				}

				.wcqf-review-button:active {
					background: var(--e-button-context-color-dark);
					color: #fff;
					text-decoration: none;
				}

				.wcqf-review-button:focus {
					outline: 0;
					background: var(--e-button-context-color-dark);
					box-shadow: 0 0 0 2px var(--e-focus-color);
					color: #fff;
					text-decoration: none;
				}
				.wcqf-review-button:hover {
					background: var(--e-button-context-color-dark);
					color: #fff;
					text-decoration: none;
				}
				.wcqf-review-button.focus {
					outline: 0;
					box-shadow: 0 0 0 2px var(--e-focus-color);
				}
				.wcqf-review-button--error {
					--e-button-context-color: #682e36;
					--e-button-context-color-dark: #ae2131;
					--e-button-context-tint: rgba(215, 43, 63, 0.04);
					--e-focus-color: rgba(215, 43, 63, 0.4);
				}
				.wcqf-review-button.wcqf-review-button--outline {
					border: 1px solid;
					background: 0 0;
					color: var(--e-button-context-color);
				}
				.wcqf-review-button.wcqf-review-button--outline:focus {
					background: var(--e-button-context-tint);
					color: var(--e-button-context-color-dark);
				}
				.wcqf-review-button.wcqf-review-button--outline:hover {
					background: var(--e-button-context-tint);
					color: var(--e-button-context-color-dark);
				}
			</style>
			<?php
		}
	}

	// Servay

	/***
	 * @param $mimes
	 *
	 * @return mixed
	 */
	public function deactivation_popup() {
		global $pagenow;
		if ( 'plugins.php' !== $pagenow ) {
			return;
		}

		$this->dialog_box_style();
		$this->deactivation_scripts();
		?>
		<div id="deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?>" title="Quick Feedback">
			<!-- Modal content -->
			<div class="modal-content">
				<div id="feedback-form-body-<?php echo esc_attr( $this->textdomain ); ?>">

					<div class="feedback-input-wrapper">
						<input id="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-bug_issue_detected" class="feedback-input"
							   type="radio" name="reason_key" value="bug_issue_detected">
						<label for="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-bug_issue_detected" class="feedback-label">Bug Or Issue detected.</label>
					</div>

					<div class="feedback-input-wrapper">
						<input id="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-no_longer_needed" class="feedback-input" type="radio"
							   name="reason_key" value="no_longer_needed">
						<label for="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-no_longer_needed" class="feedback-label">I no longer
							need the plugin</label>
					</div>
					<div class="feedback-input-wrapper">
						<input id="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-found_a_better_plugin" class="feedback-input"
							   type="radio" name="reason_key" value="found_a_better_plugin">
						<label for="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-found_a_better_plugin" class="feedback-label">I found a
							better plugin</label>
						<input class="feedback-feedback-text" type="text" name="reason_found_a_better_plugin"
							   placeholder="Please share the plugin name">
					</div>
					<div class="feedback-input-wrapper">
						<input id="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-couldnt_get_the_plugin_to_work" class="feedback-input"
							   type="radio" name="reason_key" value="couldnt_get_the_plugin_to_work">
						<label for="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-couldnt_get_the_plugin_to_work" class="feedback-label">I
							couldn't get the plugin to work</label>
					</div>

					<div class="feedback-input-wrapper">
						<input id="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-temporary_deactivation" class="feedback-input"
							   type="radio" name="reason_key" value="temporary_deactivation">
						<label for="feedback-deactivate-<?php echo esc_attr( $this->textdomain ); ?>-temporary_deactivation" class="feedback-label">It's a
							temporary deactivation</label>
					</div>
					<span style="color:red;font-size: 16px;"></span>
				</div>
				<p style="margin: 0 0 15px 0;">
					Please let us know about any issues you are facing with the plugin.
					How can we improve the plugin?
				</p>
				<div class="feedback-text-wrapper-<?php echo esc_attr( $this->textdomain ); ?>">
					<textarea id="deactivation-feedback-<?php echo esc_attr( $this->textdomain ); ?>" rows="4" cols="40"
							  placeholder=" Write something here. How can we improve the plugin?"></textarea>
					<span style="color:red;font-size: 16px;"></span>
				</div>
				<p style="margin: 0;">
					Your satisfaction is our utmost inspiration. Thank you for your feedback.
				</p>
			</div>
		</div>
		<?php
	}

	/***
	 * @param $mimes
	 *
	 * @return mixed
	 */
	public function dialog_box_style() {
		?>
		<style>
			/* Add Animation */
			@-webkit-keyframes animatetop {
				from {
					top: -300px;
					opacity: 0
				}
				to {
					top: 0;
					opacity: 1
				}
			}

			@keyframes animatetop {
				from {
					top: -300px;
					opacity: 0
				}
				to {
					top: 0;
					opacity: 1
				}
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> {
				display: none;
			}

			.ui-dialog-titlebar-close {
				display: none;
			}

			/* The Modal (background) */
			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal {
				display: none; /* Hidden by default */
				position: fixed; /* Stay in place */
				z-index: 1; /* Sit on top */
				padding-top: 100px; /* Location of the box */
				left: 0;
				top: 0;
				width: 100%; /* Full width */
				height: 100%; /* Full height */
				overflow: auto; /* Enable scroll if needed */
			}

			/* Modal Content */
			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content {
				position: relative;
				margin: auto;
				padding: 0;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .feedback-label {
				font-size: 15px;
			}

			div#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> p {
				font-size: 16px;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content > * {
				width: 100%;
				padding: 5px 2px;
				overflow: hidden;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content textarea {
				border: 1px solid rgba(0, 0, 0, 0.3);
				padding: 15px;
				width: 100%;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content input.feedback-feedback-text {
				border: 1px solid rgba(0, 0, 0, 0.3);
				min-width: 250px;
			}

			/* The Close Button */
			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"] {
				margin: 0;
			}

			.ui-dialog-title {
				font-size: 18px;
				font-weight: 600;
			}

			#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-body {
				padding: 2px 16px;
			}

			.ui-dialog-buttonset {
				background-color: #fefefe;
				padding: 0 17px 25px;
				display: flex;
				justify-content: space-between;
				gap: 10px;
			}

			.ui-dialog-buttonset button {
				min-width: 110px;
				text-align: center;
				border: 1px solid rgba(0, 0, 0, 0.1);
				padding: 0 15px;
				border-radius: 5px;
				height: 40px;
				font-size: 15px;
				font-weight: 600;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				cursor: pointer;
				transition: 0.3s all;
				background: rgba(0, 0, 0, 0.02);
				margin: 0;
			}

			.ui-dialog-buttonset button:nth-child(2) {
				background: transparent;
			}

			.ui-dialog-buttonset button:hover {
				background: #2271b1;
				color: #fff;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-wcqf"] .ui-dialog-buttonset{
				background-color: #fefefe;
				box-shadow: none;
				z-index: 99;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-wcqf"] .ui-dialog-buttonpane ,
			.ui-dialog[aria-describedby="deactivation-dialog-wcqf"] .ui-widget-content {
				border: 0;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-wcqf"] .ui-resizable-handle {
				display: none;
			}


			div#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?>,
			.ui-draggable .ui-dialog-titlebar {
				padding: 18px 15px;
				box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
				text-align: left;
			}

			.modal-content .feedback-input-wrapper {
				margin-bottom: 8px;
				display: flex;
				align-items: center;
				gap: 8px;
				line-height: 2;
				padding: 0 2px;
			}

			.ui-widget-overlay.ui-front {
				position: fixed;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				z-index: 9;
				background-color: rgba(0, 0, 0, 0.5);
			}
			.ui-dialog[aria-describedby="deactivation-dialog-wcqf"] {
				background-color: #fefefe;
				box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
				z-index: 99;
			}
			.ui-dialog[aria-describedby="deactivation-dialog-wcqf"] .ui-dialog-buttonset{
				background-color: #fefefe;
				box-shadow: none;
				z-index: 99;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-wcqf"] .ui-dialog-buttonpane ,
			.ui-dialog[aria-describedby="deactivation-dialog-wcqf"] .ui-widget-content {
				border: 0;
			}

			.ui-dialog[aria-describedby="deactivation-dialog-wcqf"] .ui-resizable-handle {
				display: none !important;
			}


		</style>

		<?php
	}

	/***
	 * @param $mimes
	 *
	 * @return mixed
	 */
	public function deactivation_scripts() {
		wp_enqueue_script( 'jquery-ui-dialog' );
		?>
		<script>
			jQuery(document).ready(function ($) {

				// Open the deactivation dialog when the 'Deactivate' link is clicked
				$('.deactivate #deactivate-cpt-wcqf').on('click', function (e) {
					e.preventDefault();
					var href = $('.deactivate #deactivate-cpt-wcqf').attr('href');
					$('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content input[name="reason_found_a_better_plugin"]').hide();
					var dialogbox = $('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?>').dialog({
						modal: true,
						width: 500,
						show: {
							effect: "fadeIn",
							duration: 400
						},
						hide: {
							effect: "fadeOut",
							duration: 100
						},

						buttons: {
							Submit: function () {
								submitFeedback();
							},
							Cancel: function () {
								$(this).dialog('close');
								window.location.href = href;
							}
						}
					});

					// Close the dialog when clicking outside of it
					dialogbox.on('change', 'input[type="radio"]', function (event) {
						var reasons = $('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"]:checked').val();
						if( 'found_a_better_plugin' === reasons ){
							$('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content input[name="reason_found_a_better_plugin"]').show();
						} else {
							$('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content input[name="reason_found_a_better_plugin"]').hide();
						}
					});

					// Close the dialog when clicking outside of it
					$(document).on('click', '.ui-widget-overlay.ui-front', function (event) {
						if ($(event.target).closest(dialogbox.parent()).length === 0) {
							dialogbox.dialog('close');
						}
					});

					// Customize the button text
					$('.ui-dialog-buttonpane button:contains("Submit")').text('Send Feedback & Deactivate');
					$('.ui-dialog-buttonpane button:contains("Cancel")').text('Skip & Deactivate');
				});

				// Submit the feedback
				function submitFeedback() {
					var href = $('.deactivate #deactivate-cpt-wcqf').attr('href');
					var reasons = $('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> input[type="radio"]:checked').val();
					var feedback = $('#deactivation-feedback-<?php echo esc_attr( $this->textdomain ); ?>').val();
					var better_plugin = $('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?> .modal-content input[name="reason_found_a_better_plugin"]').val();
					// Perform AJAX request to submit feedback
					if (!reasons && !feedback && !better_plugin) {
						// Define flag variables
						$('#feedback-form-body-<?php echo esc_attr( $this->textdomain ); ?> span').text('Choose The Reason');
						$('.feedback-text-wrapper-<?php echo esc_attr( $this->textdomain ); ?> span').text('Please provide me with some advice.');
						return;
					}

					if ('temporary_deactivation' == reasons && !feedback) {
						window.location.href = href;
					}


					$.ajax({
						url: 'http://woo-cpt.local/wp-json/TinySolutions/pluginSurvey/v1/Survey/appendToSheet',
						method: 'GET',
						dataType: 'json',
						data: {
							website: '<?php echo esc_url( home_url() ); ?>',
							reasons: reasons ? reasons : '',
							better_plugin: better_plugin,
							feedback: feedback,
							wpplugin: 'plugin-wcqf',
						},
						success: function (response) {
						},
						error: function (xhr, status, error) {
							// Handle the error response
							console.error('Error', error);
						},
						complete: function (xhr, status) {
							$('#deactivation-dialog-<?php echo esc_attr( $this->textdomain ); ?>').dialog('close');
							window.location.href = href;
						}

					});
				}

			});

		</script>

		<?php
	}
}
