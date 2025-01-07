<?php

namespace PaidCommunities\WordPress\Model;

class AbstractModel implements ModelInterface {

	private $data = [];

	public function __set( $name, $value ) {
		$this->data[ $name ] = $value;
	}

	public function __get( $name ) {
		return $this->data[ $name ] ?? null;
	}

	public function __isset( $name ) {
		return \array_key_exists( $name, $this->data );
	}
}