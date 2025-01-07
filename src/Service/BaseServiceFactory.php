<?php

namespace PaidCommunities\WordPress\Service;

class BaseServiceFactory extends AbstractServiceFactory {

	protected $mappings = [
		'plugins' => PluginService::class,
		'domains' => DomainService::class
	];

	public function __get( $name ) {
		if ( ! isset( $this->mappings[ $name ] ) ) {
			throw new \Exception( sprintf( 'Service %s has not been declared in the class mappings', $name ) );
		}

		return $this->getService( $name, $this->mappings[ $name ] );
	}
}