<?php

namespace PaidCommunities\WordPress;

use PaidCommunities\WordPress\Exception\ApiErrorException;
use PaidCommunities\WordPress\HttpClient\AbstractClient;
use PaidCommunities\WordPress\HttpClient\WordPressClient;

/**
 * Controller that manages the plugin update logic
 */
class UpdateController {

	private $config;

	public function __construct( PluginConfig $config ) {
		$this->config = $config;
	}

	public function initialize() {
		add_filter( 'update_plugins_paidcommunities.com', [ $this, 'checkPluginUpdates' ], 10, 3 );
		add_filter( 'plugins_api', [ $this, 'fetchPluginInfo' ], 10, 3 );
		Hooks::addGlobalAction( 'http_response', [ $this, 'parseHttpResponse' ], 10, 3 );
	}

	/**
	 * Given the provided plugin data, make a request to check for updates.
	 *
	 * @param $update
	 * @param $pluginData
	 * @param $pluginFile
	 *
	 * @return void
	 */
	public function checkPluginUpdates( $update, $pluginData, $pluginFile ) {
		if ( $pluginFile === $this->config->getPluginBasename() ) {
			try {
				$license = $this->config->getLicense();
				$secret  = $license->getSecret();

				$client   = new WordPressClient( $this->config->getEnvironment(), $secret );
				$response = $client->plugins->updateCheck( [
					'version'    => $pluginData['Version'],
					'product_id' => $this->config->getProductId()
				] );
				if ( $response ) {
					$update = [
						'new_version'  => $response->new_version,
						'version'      => $response->version,
						'package'      => $response->package,
						'slug'         => $response->slug,
						'icons'        => $response->icons,
						'banners'      => $response->banners,
						'tested'       => $response->tested,
						'requires'     => $response->requires,
						'requires_php' => $response->requires_php
					];
					$license->setLastCheck( $response->last_check );
					if ( $secret && isset( $response->license ) ) {
						$license->setStatus( $response->license->status );
						$license->save();
					}
				}
			} catch ( ApiErrorException $e ) {
				// add logging
				error_log( $e->getMessage() );
			}
		}

		return $update;
	}

	public function fetchPluginInfo( $response, $action, $args ) {
		if ( $action === 'plugin_information' ) {
			if ( $args->slug === $this->config->getProductId() ) {
				$license = $this->config->getLicense();
				$secret  = $license->getSecret();

				$client = new WordPressClient( $this->config->getEnvironment(), $secret );
				try {
					$response = $client->plugins->getInfo( [
						'product_id' => $args->slug
					] );
					$response = $response->toObject();
				} catch ( ApiErrorException $e ) {
					$response = new \WP_Error( 'plugin_info', $e->getMessage() );
				}
			}
		}

		return $response;
	}

	/**
	 * @param array  $response
	 * @param array  $parsed_args
	 * @param string $url
	 *
	 * @since
	 * 0.0.4
	 * @return void
	 */
	public function parseHttpResponse( $response, $parsed_args, $url ) {
		// Early return if not our API URL
		if ( ! $this->isApiUrl( $url ) ) {
			return $response;
		}

		// Only process download endpoints with non-200 responses
		if ( ! $this->isEndpoint( $url, '/downloads' ) || $this->isSuccessfulResponse( $response ) ) {
			return $response;
		}

		// Extract error message from temp file if available
		$error_message = $this->extractErrorMessageFromFile( $parsed_args );

		if ( $error_message !== null && isset( $response['response'] ) ) {
			$response['response']['message'] = $error_message;
		}

		return $response;
	}

	/**
	 * Check if URL belongs to our API
	 *
	 * @param string $url
	 *
	 * @return bool
	 */
	private function isApiUrl( $url ) {
		return strpos( $url, AbstractClient::PRODUCTION_URL ) !== false
		       || strpos( $url, AbstractClient::SANDBOX_URL ) !== false;
	}

	/**
	 * Check if URL matches a specific endpoint
	 *
	 * @param string $url
	 * @param string $endpoint
	 *
	 * @return bool
	 */
	private function isEndpoint( $url, $endpoint ) {
		return strpos( $url, $endpoint ) !== false;
	}

	/**
	 * Check if response was successful
	 *
	 * @param array $response
	 *
	 * @return bool
	 */
	private function isSuccessfulResponse( $response ) {
		return wp_remote_retrieve_response_code( $response ) === 200;
	}

	/**
	 * Extract error message from temporary file
	 *
	 * @param array $parsed_args
	 *
	 * @return string|null
	 */
	private function extractErrorMessageFromFile( $parsed_args ) {
		if ( ! is_array( $parsed_args ) || ! isset( $parsed_args['filename'] ) ) {
			return null;
		}

		$filename = $parsed_args['filename'];

		// Validate file exists and is readable
		if ( ! file_exists( $filename ) || ! is_readable( $filename ) ) {
			return null;
		}

		// Use file_get_contents instead of manual file operations
		$file_contents = file_get_contents( $filename );
		if ( $file_contents === false ) {
			return null;
		}

		$decoded_body = json_decode( $file_contents, true );

		// Validate JSON decode and structure
		if ( json_last_error() !== JSON_ERROR_NONE
		     ||
		     ! is_array( $decoded_body )
		     ||
		     ! isset( $decoded_body['message'] )
		) {
			return null;
		}

		return $decoded_body['message'];
	}

}