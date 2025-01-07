<?php

namespace PaidCommunities\WordPress\Service;

use PaidCommunities\WordPress\HttpClient\ClientInterface;
use PaidCommunities\WordPress\Model\ModelFactoryInterface;

class AbstractServiceFactory implements ServiceFactoryInterface {

	private $services = [];

	private $client;

	private $modelFactory;

	public function __construct( ClientInterface $client, ModelFactoryInterface $modelFactory ) {
		$this->client       = $client;
		$this->modelFactory = $modelFactory;
	}

	function getService( $name, $clazz ) {
		if ( ! isset( $this->services[ $name ] ) ) {
			$this->services[ $name ] = new $clazz( $this->client, $this->modelFactory );
		}

		return $this->services[ $name ];
	}

}