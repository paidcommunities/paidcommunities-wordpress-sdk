<?php

namespace PaidCommunities\WordPress;

/**
 * @since 0.0.4
 */
class Hooks {

	private static $global_hooks_registered = [];

	/**
	 * Add a hook that should only be registered once globally
	 */
	public static function addGlobalAction( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		$hook_key = self::generateHookKey( $hook, $callback );

		if ( ! isset( self::$global_hooks_registered[ $hook_key ] ) ) {
			\add_action( $hook, $callback, $priority, $accepted_args );
			self::$global_hooks_registered[ $hook_key ] = true;
		}
	}

	public static function addGlobalFilter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		$hook_key = self::generateHookKey( $hook, $callback );

		if ( ! isset( self::$global_hooks_registered[ $hook_key ] ) ) {
			\add_filter( $hook, $callback, $priority, $accepted_args );
			self::$global_hooks_registered[ $hook_key ] = true;
		}
	}

	/**
	 * Simple wrappers for WordPress add_action/add_filter
	 */
	public static function addAction( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		\add_action( $hook, $callback, $priority, $accepted_args );
	}

	public static function addFilter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		\add_filter( $hook, $callback, $priority, $accepted_args );
	}

	/**
	 * Generate a unique key for hook + callback combination
	 * @return string
	 */
	private static function generateHookKey( $hook, $callback ) {
		if ( is_array( $callback ) ) {
			return $hook . '_' . ( is_object( $callback[0] ) ? get_class( $callback[0] ) : $callback[0] ) . '_' . $callback[1];
		} elseif ( is_string( $callback ) ) {
			return $hook . '_' . $callback;
		} else {
			return $hook . '_' . spl_object_hash( $callback );
		}
	}
}