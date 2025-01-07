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

	public function toObject() {
		$object = new \stdClass();
		foreach ( $this->data as $key => $value ) {
			$object->{$key} = $this->transformValue( $value );
		}

		return $object;
	}

	private function transformValue( $value ) {
		if ( $value instanceof AbstractModel ) {
			return $value->toObject();
		}

		if ( is_array( $value ) ) {
			return array_map( [ $this, 'transformValue' ], $value );
		}

		return $value;
	}
}