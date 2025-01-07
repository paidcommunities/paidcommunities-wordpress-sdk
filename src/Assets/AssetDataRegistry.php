<?php

namespace PaidCommunities\WordPress\Assets;

final class AssetDataRegistry {

	private static $_instance;

	private $handle;

	private $registry = [];

	public function __construct( $handle ) {
		$this->handle = $handle;
	}

	private function initialize() {
		add_action( 'admin_print_footer_scripts', [ $this, 'print_script_data' ] );
	}

	public static function instance() {
		if ( ! self::$_instance ) {
			self::$_instance = new static( 'paidcommunities-wordpress-api' );
			self::$_instance->initialize();
		}

		return self::$_instance;
	}

	public function register( AssetDataApi $data ) {
		$this->registry[ $data->get_name() ] = $data;
	}

	public function get_data() {
		$data = [];
		foreach ( $this->registry as $data_api ) {
			$data[ $data_api->get_name() ] = $data_api->get_data();
		}

		return $data;
	}

	public function print_script_data() {
		$name = 'paidcommunitiesParams';
		wp_add_inline_script(
			$this->handle,
			"var $name = $name || JSON.parse( decodeURIComponent( '"
			. esc_js( rawurlencode( wp_json_encode( $this->get_data() ) ) )
			. "' ) );",
			'before'
		);
	}

}