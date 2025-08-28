<?php

namespace PaidCommunities\WordPress;

/**
 * Two-tier caching system
 *
 * Uses in-memory array for fastest access, falls back to WordPress transients
 * for persistence across requests.
 */
class Cache {

	/**
	 * In-memory cache array for fast access within the same request
	 *
	 * @var array
	 */
	private static $memory_cache = [];

	/**
	 * Default cache duration in seconds (1 minute)
	 *
	 * @var int
	 */
	private $default_duration = 60;

	/**
	 * Cache key prefix to avoid conflicts
	 *
	 * @var string
	 */
	private $key_prefix = 'paidcommunities_';

	/**
	 * Get a value from cache
	 *
	 * Checks memory cache first, then WordPress transients
	 *
	 * @param string $key Cache key
	 *
	 * @return mixed|null Returns cached value or null if not found/expired
	 */
	public function get( $key ) {
		$prefixed_key = $this->getPrefixedKey( $key );

		// Check memory cache first (fastest)
		if ( isset( self::$memory_cache[ $prefixed_key ] ) ) {
			$cached_data = self::$memory_cache[ $prefixed_key ];

			// Check if still valid
			if ( time() <= $cached_data['expires_at'] ) {
				return $cached_data['data'];
			}

			// Expired - remove from memory cache
			unset( self::$memory_cache[ $prefixed_key ] );
		}

		// Check WordPress transient cache
		$cached_value = get_transient( $prefixed_key );

		if ( $cached_value !== false ) {
			// Found in transient - store in memory cache for subsequent calls
			self::$memory_cache[ $prefixed_key ] = [
				'data'       => $cached_value,
				'expires_at' => time() + $this->default_duration
			];

			return $cached_value;
		}

		return null;
	}

	/**
	 * Check if a key exists in cache and is not expired
	 *
	 * @param string $key Cache key
	 *
	 * @return bool True if key exists and is valid
	 */
	public function has( $key ) {
		return $this->get( $key ) !== null;
	}

	/**
	 * Set a value in cache
	 *
	 * Stores in both memory cache and WordPress transients
	 *
	 * @param string $key Cache key
	 * @param mixed $data Data to cache
	 * @param int|null $duration Cache duration in seconds (uses default if null)
	 *
	 * @return bool True on success, false on failure
	 */
	public function set( $key, $data, $duration = null ) {
		$duration     = $duration ?? $this->default_duration;
		$prefixed_key = $this->getPrefixedKey( $key );

		// Store in memory cache
		self::$memory_cache[ $prefixed_key ] = [
			'data'       => $data,
			'expires_at' => time() + $duration
		];

		// Store in WordPress transient
		return set_transient( $prefixed_key, $data, $duration );
	}

	/**
	 * Delete a specific key from cache
	 *
	 * @param string $key Cache key
	 *
	 * @return bool True on success
	 */
	public function delete( $key ) {
		$prefixed_key = $this->getPrefixedKey( $key );

		// Remove from memory cache
		unset( self::$memory_cache[ $prefixed_key ] );

		// Remove from WordPress transient
		return delete_transient( $prefixed_key );
	}

	/**
	 * Clear all cache entries
	 *
	 * Note: This only clears memory cache and known transients.
	 * WordPress doesn't provide a way to clear all transients by prefix.
	 *
	 * @return void
	 */
	public function clear() {
		// Clear memory cache
		self::$memory_cache = [];

		// Note: WordPress doesn't have a built-in way to delete transients by prefix
		// You'd need to track keys or use a custom implementation
	}

	/**
	 * Generate a cache key from multiple parameters
	 *
	 * @param string ...$parts Variable number of strings to create cache key from
	 *
	 * @return string Generated cache key
	 */
	public function generateKey( ...$parts ) {
		return md5( implode( '|', $parts ) );
	}

	/**
	 * Get prefixed cache key
	 *
	 * @param string $key Original key
	 *
	 * @return string Prefixed key
	 */
	private function getPrefixedKey( $key ) {
		return $this->key_prefix . $key;
	}
}