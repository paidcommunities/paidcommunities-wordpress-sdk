<?php

namespace PaidCommunities\WordPress\Assets;

class AssetDataApi {

	private $data = [];

	/**
	 * @param mixed $key
	 * @param       $data
	 *
	 * @return void
	 */
	public function add( mixed $key, $data = null ) {
		if ( \is_array( $key ) ) {
			$this->data = $key;
		} else {
			$this->data[ $key ] = $data;
		}
	}

	public function get( $key, $default = null ) {
		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : $default;
	}

	public function remove( $key ) {
		unset( $this->data[ $key ] );
	}

	public function get_data() {
		return $this->data;
	}

	public function has_data() {
		return ! empty( $this->data );
	}

	public function print_data( $name ) {
		$data = rawurlencode( wp_json_encode( $this->data ) );
		echo "<script id=\"$name\">
				window['$name'] = JSON.parse( decodeURIComponent( '" . esc_js( $data ) . "' ) );
		</script>";
	}

	public function add_inline_script( $name, $handle ) {
		wp_add_inline_script(
			$handle,
			"var $name = $name || JSON.parse( decodeURIComponent( '"
			. esc_js( rawurlencode( wp_json_encode( $this->data ) ) )
			. "' ) );",
			'before'
		);
	}

}
