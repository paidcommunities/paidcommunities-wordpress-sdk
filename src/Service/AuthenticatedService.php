<?php

namespace PaidCommunities\WordPress\Service;

class AuthenticatedService extends AbstractService {

	public function request( $method, $path, $request = [], $model = null, $opts = [] ) {
		if ( $this->isAuthenticated( $method ) ) {
			$opts['headers'] = $this->client->getAuthorizationHeader();
		}

		return parent::request( $method, $path, $request, $model, $opts );
	}

	protected function isAuthenticated( $method ) {
		return true;
	}
}