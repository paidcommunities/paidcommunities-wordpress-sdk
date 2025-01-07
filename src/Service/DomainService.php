<?php

namespace PaidCommunities\WordPress\Service;

use PaidCommunities\WordPress\Model\Domain;

class DomainService extends AuthenticatedService {

	protected $path = '/domains';

	public function delete( $id ) {
		return $this->request( 'delete', $this->buildPath( '/%s', $id ) );
	}

	/**
	 * @param $license
	 * @param $request
	 *
	 * @return Domain
	 */
	public function register( $request ) {
		return $this->post( $this->buildPath( '' ), $request, Domain::class );
	}
}