<?php

namespace PaidCommunities\WordPress\Assets;

/**
 * Class that manages all script and style registration and equeue logic
 */
class AssetsApi {

	private $assets_url;

	private $basePath;

	private $version;

	public function __construct( $basePath, $assets_url, $version = null ) {
		$this->basePath   = $basePath;
		$this->assets_url = $assets_url;
		$this->version    = $version;
	}

	public function register_script( $handle, $relative_path, $deps = [], $version = null, $footer = true ) {
		$relative_path = '/' . ltrim( $relative_path, '\/' );
		$file_name     = str_replace( '.js', '.asset.php', $relative_path );
		$file          = $this->basePath . $file_name;
		$version       = null;
		if ( file_exists( $file ) ) {
			$assets  = include $file;
			$version = $assets['version'] ?? $version;
			if ( isset( $assets['dependencies'] ) ) {
				$deps = array_merge( $assets['dependencies'], $deps );
			}
		}

		wp_register_script( $handle, $this->assets_url( $relative_path ), $deps, $version, $footer );
	}

	public function register_style( $handle, $relative_path, $deps = [], $version = null ) {
		$version = is_null( $version ) ? $this->version : $version;
		wp_register_style( $handle, $this->assets_url( $relative_path ), $deps, $version );
	}

	public function assets_url( $relative_path ) {
		return $this->assets_url . trim( $relative_path, '/' );
	}

}