<?php
/**
 * Template: Test Template
 *
 * @package Prefix\ExtraProductOptions
 * @since   1.0.0
 */

?>
<p>
	<?php
	echo esc_html__( 'This is being loaded inside "wp_footer" from the templates class', 'wc-quantity-field' ) . ' ' . esc_html( $args['data']['text'] );
	?>
</p>
