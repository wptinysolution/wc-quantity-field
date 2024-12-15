<?php
/**
 *
 */
namespace TinySolutions\wp_quantity_field\Abs;

// Do not allow directly accessing this file.
use TinySolutions\wp_quantity_field\Common\Loader;
use TinySolutions\wp_quantity_field\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
/**
 * Custom Post Type
 */
abstract class CustomPostType {

	/**
	 * @var object
	 */
	protected $loader;

	/**
	 * Class Constructor
	 */
	protected function __construct() {
		$this->loader = Loader::instance();
		$this->loader->add_action( 'init', $this, 'init_post_type' );
	}
	/**
	 * Init Run $this->register_post_type and $this->add_taxonomy()
	 *
	 * @return void
	 */
	public function init_post_type() {
		$this->register_post_type();
		if ( method_exists( $this, 'add_taxonomy' ) ) {
			$this->add_taxonomy();
		}
	}
	/**
	 * Init $post_type_name
	 *
	 * @return string
	 */
	abstract public function set_post_type_name();
	/**
	 * Set_post_type_args
	 *
	 * @return array
	 */
	protected function set_post_type_args() {
		return [];
	}
	/**
	 * Post_type_labels
	 *
	 * @return array;
	 */
	protected function set_post_type_labels() {
		return [];
	}
	/**
	 * @return void
	 */
	private function register_post_type() {
		$post_type_name   = Fns::uglify( $this->set_post_type_name() );
		$post_type_args   = $this->set_post_type_args() ?? [];
		$post_type_labels = $this->set_post_type_labels() ?? [];
		// Capitalize the words and make it plural.
		$name   = Fns::beautify( $post_type_name );
		$plural = Fns::pluralize( $name );
		// We set the default labels based on the post type name and plural. We overwrite them with the given labels.
		$defaults_labels = [
			/* translators: %s: post-type name */
			'add_new'            => sprintf( esc_html_x( 'Add New', '%s', 'wc-quantity-field' ), strtolower( $name ) ),
			/* translators: %s: post-type name */
			'add_new_item'       => sprintf( esc_html__( 'Add New %s', 'wc-quantity-field' ), $name ),
			/* translators: %s: post-type name */
			'edit_item'          => sprintf( esc_html__( 'Edit %s', 'wc-quantity-field' ), $name ),
			/* translators: %s: post-type name */
			'new_item'           => sprintf( esc_html__( 'New %s', 'wc-quantity-field' ), $name ),
			/* translators: %s: post-type plural name */
			'all_items'          => sprintf( esc_html__( 'All %s', 'wc-quantity-field' ), $plural ),
			/* translators: %s: post-type name */
			'view_item'          => sprintf( esc_html__( 'View %s', 'wc-quantity-field' ), $name ),
			/* translators: %s: post-type plural name */
			'search_items'       => sprintf( esc_html__( 'Search %s', 'wc-quantity-field' ), $plural ),
			/* translators: %s: post-type plural name */
			'not_found'          => sprintf( esc_html__( 'No %s found', 'wc-quantity-field' ), strtolower( $plural ) ),
			/* translators: %s: post-type plural name */
			'not_found_in_trash' => sprintf( esc_html__( 'No %s found in Trash', 'wc-quantity-field' ), strtolower( $plural ) ),
			/* translators: %s: post-type plural name */
			'parent_item_colon'  => sprintf( esc_html__( 'Parent %s: ', 'wc-quantity-field' ), $plural ),
			'menu_name'          => $name,
		];
		$labels          = wp_parse_args( $post_type_labels, $defaults_labels );
		// Same principle as the labels. We set some defaults and overwrite them with the given arguments.
		$defaults_args  = [
			'public'            => true,
			'show_ui'           => true,
			'supports'          => [ 'title', 'editor' ],
			'show_in_nav_menus' => true,
			'has_archive'       => true,
		];
		$args           = wp_parse_args( $post_type_args, $defaults_args );
		$args['labels'] = $labels;
		// Register the post type.
		register_post_type( $post_type_name, $args );
	}
}
