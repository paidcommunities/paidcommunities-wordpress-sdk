<?php

namespace PaidCommunities\WordPress\Exception;

class ApiErrorException extends \Exception {

	private $param;

	private $errorCode;

	public function __construct( $message = "", $code = 0, ?\Throwable $previous = null ) {
		parent::__construct( $message, $code, $previous );
	}

	/**
	 * Builds an exception from an API error response. Always returns an exception instance,
	 * even if the response body is empty or doesn't match a known error format.
	 *
	 * @param int $code The HTTP status code.
	 * @param mixed $response The decoded response body.
	 *
	 * @return static
	 */
	public static function factory( $code, $response ) {
		$message = '';
		$param   = '';

		if ( is_array( $response ) ) {
			if ( isset( $response['errors'][0] ) && is_array( $response['errors'][0] ) ) {
				$message = $response['errors'][0]['message'] ?? '';
				$param   = $response['errors'][0]['param'] ?? '';
			} elseif ( isset( $response['error'] ) && is_array( $response['error'] ) ) {
				$message = $response['error']['message'] ?? '';
				$param   = $response['error']['param'] ?? '';
			} elseif ( isset( $response['message'] ) ) {
				$message = $response['message'];
			}
		}

		if ( ! is_string( $message ) || $message === '' ) {
			$message = sprintf( 'PaidCommunities API request failed with HTTP status %s.', $code );
		}

		$exception = new static( $message, (int) $code );
		$exception->setParam( $param );

		return $exception;
	}

	public function getParam() {
		return $this->param;
	}

	public function setParam( $param ) {
		$this->param = $param;
	}

	public function getErrorCode() {
		return $this->errorCode;
	}

	public function setErrorCode( $code ) {
		$this->errorCode = $code;
	}

}