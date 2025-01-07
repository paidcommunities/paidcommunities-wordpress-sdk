<?php

namespace PaidCommunities\WordPress\Service;

use PaidCommunities\WordPress\Model\PluginInfoResponse;
use PaidCommunities\WordPress\Model\PluginUpdateResponse;

class PluginService extends AuthenticatedService {

	protected $path = '/plugins';

	/**
	 * @param $request
	 *
	 * @return PluginUpdateResponse
	 */
	public function updateCheck( $request ) {
		return $this->post( $this->buildPath( '/update_check' ), $request, PluginUpdateResponse::class );
	}

	/**
	 * @param $request
	 *
	 * @return PluginInfoResponse
	 * @throws \PaidCommunities\Exception\ApiErrorException
	 */
	public function getInfo( $request ) {
		return $this->get( $this->buildPath( '/info' ), $request, PluginInfoResponse::class );
	}
}