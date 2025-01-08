<?php

namespace PaidCommunities\WordPress\HttpClient;

use PaidCommunities\WordPress\Exception\AuthenticationException;
use PaidCommunities\WordPress\Exception\AuthorizationException;
use PaidCommunities\WordPress\Exception\BadRequestException;
use PaidCommunities\WordPress\Exception\NotFoundException;
use PaidCommunities\WordPress\Util\GeneralUtils;

abstract class AbstractClient implements ClientInterface {

	const SANDBOX = 'sandbox';

	const PRODUCTION = 'production';

	const SANDBOX_URL = 'https://api-development.paidcommunities.com/wordpress/v1';

	const PRODUCTION_URL = 'https://api.paidcommunities.com/wordpress/v1';

	const GET = 'GET';

	const POST = 'POST';

	const PUT = 'PUT';

	const DELETE = 'DELETE';

	private $environment;

	private $secret;

	protected $serviceFactory;

	public function __construct( $environment = self::PRODUCTION, $secret = null ) {
		$this->environment = $environment;
		$this->secret      = $secret;
	}

	/**
	 * @param $method
	 * @param $path
	 * @param $request
	 * @param $opts
	 *
	 * @return mixed
	 */
	abstract function request( $method, $path, $request, $opts );

	public function get( $path, $opts = [] ) {
		return $this->request( 'get', $path, $opts );
	}

	public function post( $path, $args = [], $opts = [] ) {
		return $this->request( 'post', $path, $args, $opts );
	}

	public function put( $path, $args = [], $opts = [] ) {
		return $this->request( 'put', $path, $args, $opts );
	}

	public function delete( $path, $opts = [] ) {
		return $this->request( 'delete', $path, null, $opts );
	}

	protected function handleStatusCode( $code, $body ) {
		switch ( $code ) {
			case 400:
				throw BadRequestException::factory( $code, $body );
			case 401:
				throw AuthenticationException::factory( $code, $body );
			case 403:
				throw AuthorizationException::factory( $code, $body );
			case 404:
				throw NotFoundException::factory( $code, $body );
			case 405:
				break;
		}
	}

	public function getSecret() {
		return $this->secret;
	}

	public function setSecret( $secret ) {
		$this->secret = $secret;
	}

	public function getBaseUrl() {
		switch ( $this->environment ) {
			case self::PRODUCTION:
				return self::PRODUCTION_URL;
			case self::SANDBOX:
				return self::SANDBOX_URL;
		}
	}

	protected function buildUrl( $path ) {
		return $this->getBaseUrl() . '/' . GeneralUtils::trimPath( $path );
	}

	public function prepareRequest( $body = null ) {
		$args    = [];
		$headers = $this->getHeaders();
		if ( $body && $headers['Content-Type'] === 'application/json' ) {
			$body = json_encode( $body );
		}

		return [ $headers, $body ];
	}

	protected function getHeaders() {
		return [
			'Content-Type' => 'application/json'
		];
	}

	public function getAuthorizationHeader() {
		return [
			'Authorization' => 'Bearer ' . $this->secret
		];
	}
}