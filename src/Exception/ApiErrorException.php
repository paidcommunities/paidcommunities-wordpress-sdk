<?php

namespace PaidCommunities\WordPress\Exception;

class ApiErrorException extends \Exception {

	private $param;

	private $errorCode;

	public function __construct( $message = "", $code = 0, Throwable $previous = null ) {
		parent::__construct( $message, $code, $previous );
	}

	public static function factory( $code, $response ) {
		$exception = null;
		if ( isset( $response['errors'] ) ) {
			$error = $response['errors'][0];

			$exception = new static( $error['message'] ?? '', $code );
			$exception->setParam( $error['param'] ?? '' );
		} elseif ( isset( $response['error'] ) ) {
			$exception = new static( $response['error']['message'] ?? '', $code );
			$exception->setParam( $error['param'] ?? '' );
		} elseif ( isset( $response['message'] ) ) {
			$exception = new static( $response['message'], $code );
		}

		return $exception;
	}

	public function setParam( $param ) {
		$this->param = $param;
	}

	public function setErrorCode( $code ) {
		$this->errorCode = $code;
	}

}