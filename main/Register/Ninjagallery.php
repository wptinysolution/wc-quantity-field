<?php

namespace TinySolutions\wp_quantity_field\Register;

use TinySolutions\wp_quantity_field\Traits\SingletonTrait;
use TinySolutions\wp_quantity_field\Abs\CustomPostType;
use TinySolutions\wp_quantity_field\Traits\Taxonomies;

class Ninjagallery extends CustomPostType {
	
	/**
	 * Singleton
	 */
	use SingletonTrait;
	
	/**
	 * Register Taxonomies
	 */
	use Taxonomies;

	/**
	 * Post type name
	 *
	 * @return Post type name
	 */
	function set_post_type_name() {
		return 'News';
	}

	/**
	 * Post type labels
	 *
	 * @return Post type labels
	 */
	function set_post_type_labels() {
		return [
			'name'               => _x( 'News', 'post type general name' ),
			'menu_name'          => 'News',
			'all_items'          => __( 'All News' ),
			'search_items'       => __( 'Search News' ),
			'not_found'          => __( 'No News found' ),
			'not_found_in_trash' => __( 'No News found in Trash' ),
		];
	}

	/**
	 * Post type args
	 *
	 * @return Post type args
	 */
	function set_post_type_args() {
		return [
			'has_archive' => false,
			'rewrite'     => [ 'slug' => 'news-list' ],
			// 'publicly_queryable' => false,
			// 'label'                 => 'News',
		];
	}
	/**
	 * Texonomy Name
	 *
	 * @return Texonomy Name
	 */
	public function set_taxonomy_name() {
		return 'News Type';
	}
	/*
	* texonomy set_taxonomy_labels
	* @return set_taxonomy_labels
	*/
	function set_taxonomy_labels() {
		return [
			'name'      => _x( 'Writers', 'taxonomy general name', 'textdomain' ),
			'menu_name' => __( 'Writers' ),
		];
	}
	/*
	* texonomy set_taxonomy_args
	* @return set_taxonomy_labels
	*/
	function set_taxonomy_args() {
		return [
			'label'   => 'XXX',
			'rewrite' => [ 'slug' => 'news-writer' ],
		];
	}
}
