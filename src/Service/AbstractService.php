<?php

namespace PaidCommunities\WordPress\Service;

use PaidCommunities\WordPress\Exception\ApiErrorException;
use PaidCommunities\WordPress\HttpClient\ClientInterface;
use PaidCommunities\WordPress\Model\ModelFactoryInterface;
use PaidCommunities\WordPress\Util\GeneralUtils;

/**
 *
 */
class AbstractService implements ServiceInterface {

	protected $client;

	private $models;

	protected $path = '';

	public function __construct( ClientInterface $client, ModelFactoryInterface $models ) {
		$this->client = $client;
		$this->models = $models;
	}

	/**
	 * @param $method
	 * @param $path
	 * @param $request
	 * @param $model
	 * @param $opts
	 *
	 * @return mixed
	 * @throws ApiErrorException
	 */
	public function request( $method, $path, $request = [], $model = null, $opts = [] ) {
		$response = $this->client->request( $method, $path, $request, $opts );
		if ( $response ) {
			return $this->models->buildModel( $model, $response );
		}

		return $response;
	}

	public function retrieve( $id ) {
		return $this->request( 'get', $this->buildPath( '' ) );
	}

	public function post( $path, $request, $model = null ) {
		return $this->request( 'post', $path, $request, $model );
	}

	public function get( $path, $request = [], $model = null ) {
		return $this->request( 'get', $path, $request, $model );
	}

	public function put( $path, $request, $model = null ) {
		return $this->request( 'put', $path, $request, $model );
	}

	protected function buildPath( $path = '', ...$args ) {
		if ( $path ) {
			$path = '/' . GeneralUtils::trimPath( $path );
		}
		if ( $this->path ) {
			$path = '/' . GeneralUtils::trimPath( $this->path ) . $path;
		}

		return sprintf( $path, ...array_map( '\urlencode', $args ) );
	}
}