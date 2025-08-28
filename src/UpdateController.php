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
	 * @param array $response
	 * @param array $parsed_args
	 * @param string $url
	 *
	 * @return void
	 * @since
	 * 0.0.4
	 */
	public function parseHttpResponse( $response, $parsed_args, $url ) {
		if ( strpos( $url, AbstractClient::PRODUCTION_URL ) !== false || strpos( $url, AbstractClient::SANDBOX_URL ) !== false ) {
			if ( strpos( $url, '/wordpress/v1/downloads' ) !== false ) {
				$response_code = wp_remote_retrieve_response_code( $response );

				if ( 200 !== $response_code ) {
					if ( \is_array( $parsed_args ) && isset( $parsed_args['filename'] ) ) {
						$tmpf = fopen( $parsed_args['filename'], 'rb' );
						if ( $tmpf ) {
							$body = fread( $tmpf, filesize( $parsed_args['filename'] ) );
							$body = json_decode( $body, true );
							fclose( $tmpf );

							if ( \is_array( $body ) && isset( $body['message'], $response['response'] ) ) {
								$response['response']['message'] = $body['message'];
							}
						}
					}
				}
			}
		}

		return $response;
	}

}