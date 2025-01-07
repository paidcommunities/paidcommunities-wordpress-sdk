<?php

namespace PaidCommunities\WordPress\Exception;

class NotFoundException extends ApiErrorException {

	public static function factory( $code, $response ) {
		$exception = parent::factory( $code, $response );
		if ( ! $exception ) {
			return new NotFoundException( 'Not found', 404 );
		}
	}

}