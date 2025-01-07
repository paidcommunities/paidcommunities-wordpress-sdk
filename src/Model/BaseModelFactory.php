<?php

namespace PaidCommunities\WordPress\Model;

class BaseModelFactory extends AbstractModelFactory {

	private $mappings = [
		'update'  => PluginUpdateResponse::class
	];

	protected function getModelClass( $name ) {
		return $this->mappings[ $name ] ?? null;
	}

	protected function hasClass( $name ) {
		return isset( $this->mappings[ $name ] );
	}
}