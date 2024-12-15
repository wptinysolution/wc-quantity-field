<?php
/**
 *
 */
namespace TinySolutions\wp_quantity_field\Common;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}
/**
 * Loader
 */
class Loader {

	/**
	 * The single instance of the class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * @return self
	 */
	final public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $actions The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $filters The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	private function __construct() {
		$this->actions = [];
		$this->filters = [];
	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @param string            $hook The name of the WordPress action that is being registered.
	 * @param object            $component A reference to the instance of the object on which the action is defined.
	 * @param callable          $callback The name of the function definition on the $component.
	 * @param int      Optional $priority         The priority at which the function should be fired.
	 * @param int      Optional $accepted_args    The number of arguments that should be passed to the $callback.
	 *
	 * @since    1.0.0
	 */
	public function add_action( string $hook, object $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @param string            $hook The name of the WordPress filter that is being registered.
	 * @param object            $component A reference to the instance of the object on which the filter is defined.
	 * @param callable          $callback The name of the function definition on the $component.
	 * @param int      Optional $priority         The priority at which the function should be fired.
	 * @param int      Optional $accepted_args    The number of arguments that should be passed to the $callback.
	 *
	 * @since    1.0.0
	 */
	public function add_filter( string $hook, object $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @param array             $hooks The collection of hooks that is being registered (that is, actions or filters).
	 * @param string            $hook The name of the WordPress filter that is being registered.
	 * @param object            $component A reference to the instance of the object on which the filter is defined.
	 * @param callable          $callback The name of the function definition on the $component.
	 * @param int      Optional $priority         The priority at which the function should be fired.
	 * @param int      Optional $accepted_args    The number of arguments that should be passed to the $callback.
	 *
	 * @return   array                                   The collection of actions and filters registered with WordPress.
	 * @since    1.0.0
	 * @access   private
	 */
	private function add( array $hooks, string $hook, object $component, $callback, $priority, $accepted_args ) {

		$hooks[] = [
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		];

		return $hooks;
	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			\add_filter(
				$hook['hook'],
				[
					$hook['component'],
					$hook['callback'],
				],
				$hook['priority'],
				$hook['accepted_args']
			);
		}

		foreach ( $this->actions as $hook ) {
			\add_action(
				$hook['hook'],
				[
					$hook['component'],
					$hook['callback'],
				],
				$hook['priority'],
				$hook['accepted_args']
			);
		}
	}
}
