<?php

namespace PaidCommunities\WordPress\Service;

use PaidCommunities\WordPress\Model\Domain;

class DomainService extends AuthenticatedService {

	protected $path = '/domains';

	/**
	 * @param $id
	 *
	 * @return mixed
	 * @throws \PaidCommunities\WordPress\Exception\ApiErrorException
	 */
	public function delete( $id ) {
		return $this->request( 'delete', $this->buildPath( '/%s', $id ) );
	}

	/**
	 * @param $license
	 * @param $request
	 *
	 * @return Domain
	 * @throws \PaidCommunities\WordPress\Exception\ApiErrorException
	 */
	public function register( $request ) {
		return $this->post( $this->buildPath( '' ), $request, Domain::class );
	}
}