<?php
/**
 *
 */

namespace TinySolutions\wcqf\Traits;

// Do not allow directly accessing this file.
use TinySolutions\wcqf\Helpers\Fns;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

trait Taxonomies {

	/**
	 * init $post_type_name
	 *
	 * @return set $post_type_name
	 */
	abstract function set_taxonomy_name();
	/**
	 * set_post_type_args
	 *
	 * @param [type] array
	 * @return set post_type_args
	 */
	protected function set_taxonomy_args() {}
	/**
	 * post_type_labels
	 *
	 * @param [type] array
	 * @return set $post_type_labels;
	 */
	protected function set_taxonomy_labels() {}
	/**
	 * add_taxonomy
	 *
	 * @return set add_taxonomy;
	 */
	public function add_taxonomy() {
		if ( ! empty( $this->set_taxonomy_name() ) ) {
			// echo "Hello " ;
			// We need to know the post type name, so the new taxonomy can be attached to it.
			$post_type_name = Fns::uglify( $this->set_post_type_name() );
			// $name = $this->post_taxonomy_name;
			// Taxonomy properties
			$taxonomy_name   = Fns::uglify( $this->set_taxonomy_name() );
			$taxonomy_labels = $this->set_taxonomy_labels() ?? [];
			$taxonomy_args   = $this->set_taxonomy_args() ?? [];
			/* More code coming */
			if ( ! taxonomy_exists( $taxonomy_name ) ) {
				// Capitalize the words and make it plural.
				$name   = Fns::beautify( $taxonomy_name );
				$plural = Fns::pluralize( $name );
				// Default labels, overwrite them with the given labels.
				$defaults_labels = [
					'name'              => _x( $plural, 'taxonomy general name' ),
					'singular_name'     => _x( $name, 'taxonomy singular name' ),
					'search_items'      => __( 'Search ' . $plural, 'kkk' ),
					'all_items'         => __( 'All ' . $plural ),
					'parent_item'       => __( 'Parent ' . $name ),
					'parent_item_colon' => __( 'Parent ' . $name . ':' ),
					'edit_item'         => __( 'Edit ' . $name ),
					'update_item'       => __( 'Update ' . $name ),
					'add_new_item'      => __( 'Add New ' . $name ),
					'new_item_name'     => __( 'New ' . $name . ' Name' ),
					'menu_name'         => __( $name ),
				];
				$labels          = wp_parse_args( $taxonomy_labels, $defaults_labels );
				// Default arguments, overwritten with the given arguments.
				$defaults_args  = [
					'public'            => true,
					'show_ui'           => true,
					'show_in_nav_menus' => true,
					'hierarchical'      => true,
					'_builtin'          => false,
				];
				$args           = wp_parse_args( $taxonomy_args, $defaults_args );
				$args['labels'] = $labels;
				// Add the taxonomy to the post type.
				register_taxonomy( $taxonomy_name, $post_type_name, $args );
			}
		}
	}
}
