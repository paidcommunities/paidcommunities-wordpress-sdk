<?php

namespace PaidCommunities\WordPress\HttpClient;

use PaidCommunities\WordPress\Exception\ApiErrorException;

interface ClientInterface {

	/**
	 * @param $method
	 * @param $path
	 * @param $request
	 * @param $opts
	 *
	 * @return mixed
	 * @throws ApiErrorException
	 */
	public function request( $method, $path, $request, $opts );

	function getBaseUrl();
}