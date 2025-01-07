<?php

namespace PaidCommunities\WordPress\Util;

class GeneralUtils {

	public static function trimPath( $path ) {
		return \ltrim( \rtrim( $path, '/\\' ), '/\\' );
	}

	public static function isList( $value ) {
		if ( ! \is_array( $value ) ) {
			return false;
		}
		if ( array() === $value ) {
			return true;
		} elseif ( \array_keys( $value ) === \range( 0, count( $value ) - 1 ) ) {
			return true;
		}

		return false;
	}

	public static function redactString( $string, $last = 4 ) {
		$length = strlen( $string ) - $last;

		return implode( '', array_fill( 0, $length, '*' ) ) . substr( $string, $length );
	}

	public static function createNonce( $key ) {
		return wp_create_nonce( $key . '-action' );
	}

	public static function formatPluginName( $value ) {
		return preg_replace( [ '/\//', '/\./' ], [ '_', '__' ], $value );
	}

	/**
	 * @param $pluginFile
	 *
	 * @return mixed|string
	 * @since 1.0.1
	 */
	public static function parsePluginVersion( $pluginFile ) {
		$data['Version'] = '';
		if ( file_exists( $pluginFile ) ) {
			if ( ! function_exists( 'get_file_data' ) ) {
				require_once ABSPATH . 'wp-includes/functions.php';
			}
			$data = \get_file_data( $pluginFile, [ 'Version' => 'Version' ], 'plugin' );
		}

		return $data['Version'] ?? '';
	}

}