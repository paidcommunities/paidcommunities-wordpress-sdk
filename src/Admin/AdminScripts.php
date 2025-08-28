<?php

namespace PaidCommunities\WordPress\Admin;

use PaidCommunities\WordPress\Assets\AssetDataApi;
use PaidCommunities\WordPress\Assets\AssetsApi;
use PaidCommunities\WordPress\Hooks;
use PaidCommunities\WordPress\PluginConfig;

class AdminScripts {

	private $config;

	private $assets;

	public function __construct( PluginConfig $config, AssetsApi $assets ) {
		$this->config = $config;
		$this->assets = $assets;
	}

	public function initialize() {
		Hooks::addGlobalAction( 'admin_init', [ $this, 'register_scripts' ] );
	}

	public function register_scripts() {
		$this->assets->register_script( 'paidcommunities-license', 'build/license-settings.js' );
		$this->assets->register_script( 'paidcommunities-wp-api', 'build/api.js' );
		$this->assets->register_script( 'paidcommunities-admin-license', 'build/admin-license.js' );
		$this->assets->register_script( 'paidcommunities-wp-components', 'build/components.js' );
		$this->assets->register_style( 'paidcommunities-wp-components', 'build/styles.css' );
	}

}