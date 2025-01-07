<?php

namespace PaidCommunities\WordPress\Admin;

use PaidCommunities\WordPress\Util\GeneralUtils;
use PaidCommunities\WordPress\HttpClient\WordPressClient;
use PaidCommunities\WordPress\License;
use PaidCommunities\WordPress\PluginConfig;

class AdminAjaxController {

	private $name;

	private $config;

	private $actions = [
		'activate'   => 'activate_',
		'deactivate' => 'deactivate_'
	];

	public function __construct( PluginConfig $config ) {
		$this->name   = $config->getPluginBasename();
		$this->config = $config;
		$this->initialize();
	}

	private function initialize() {
		add_action( 'wp_ajax_' . $this->getActions()->activate . $this->name, [ $this, 'handleLicenseActivate' ] );
		add_action( 'wp_ajax_' . $this->getActions()->deactivate . $this->name, [ $this, 'handleLicenseDeactivate' ] );
	}

	private function getActions() {
		return (object) $this->actions;
	}

	public function handleLicenseActivate() {
		// use the license key to activate the domain
		$license    = $this->config->getLicense();
		$client     = new WordPressClient( $this->config->getEnvironment() );
		$licenseKey = $_POST['license_key'] ?? '';
		$domain     = $_SERVER['SERVER_NAME'] ?? '';
		try {
			$this->verifyAdminNonce();

			if ( ! current_user_can( 'administrator' ) ) {
				throw new \Exception( __( 'Administrator access is required to perform this action.', 'paidcommunities' ), 403 );
			}
			if ( ! $licenseKey ) {
				throw new \Exception( __( 'Please provide a license key', 'paidcommunities' ) );
			}
			if ( ! $domain ) {
				$domain = $_SERVER['HTTP_HOST'];
			}

			$metadata = apply_filters( 'domain_metadata_' . $this->config->getPluginBasename(), [] );

			$domain = $client->domains->register( [
				'key'        => $licenseKey,
				'domain'     => $domain,
				'version'    => $this->config->getVersion(),
				'product_id' => $this->config->getProductId(),
				'metadata'   => $metadata
			] );

			$license->setLicenseKey( GeneralUtils::redactString( $licenseKey, 8 ) );
			$license->setStatus( License::ACTIVE );
			$license->setSecret( $domain->secret );
			$license->setDomain( $domain->domain );
			$license->setDomainId( $domain->id );
			$license->setCreatedAt( $domain->created_at );
			$license->save();

			ob_start();
			$this->config->getLicenseSettings()->render();
			$html = ob_get_clean();

			$this->sendAjaxSuccessResponse( [
				'license' => $license->toArray(),
				'notice'  => [
					'code'    => 'activation_success',
					'message' => __( 'Your site has been activated.', 'paidcommunities' )
				],
				'html'    => $html,
				'license' => [
					'domain'      => $license->getDomain(),
					'domain_id'   => $license->getDomainId(),
					'registered'  => $license->isRegistered(),
					'license_key' => $license->getLicenseKey()
				]
			] );
		} catch ( \Exception $e ) {
			$this->sendAjaxErrorResponse( $e );
		}
	}

	public function handleLicenseDeactivate() {
		$license = $this->config->getLicense();
		$client  = new WordPressClient( $this->config->getEnvironment() );
		try {
			$this->verifyAdminNonce();

			if ( ! current_user_can( 'administrator' ) ) {
				throw new \Exception( __( 'Administrator access is required to perform this action.', 'paidcommunities' ), 403 );
			}

			$id = $license->getDomainId();

			if ( ! $id ) {
				throw new \Exception( __( 'Domain ID cannot be empty. Are you sure you have a registered domain?', 'paidcommunities' ) );
			}
			$client->setSecret( $license->getSecret() );

			$client->domains->delete( $id );

			$license->delete();

			ob_start();
			$this->config->getLicenseSettings()->render();
			$html = ob_get_clean();

			$this->sendAjaxSuccessResponse( [
				'notice'  => [
					'code'    => 'deactivation_success',
					'message' => esc_html__( 'Your site has been deactivated.', 'paidcommunities' ),
				],
				'html'    => $html,
				'license' => [
					'domain'      => $license->getDomain(),
					'domain_id'   => $license->getDomainId(),
					'registered'  => $license->isRegistered(),
					'license_key' => $license->getLicenseKey()
				]
			] );
		} catch ( \Exception $e ) {
			$license->delete();
			$this->sendAjaxErrorResponse( $e );
		}
	}

	private function sendAjaxSuccessResponse( $data ) {
		\wp_send_json( [
			'success' => true,
			'data'    => $data
		] );
	}

	private function sendAjaxErrorResponse( $e ) {
		\wp_send_json( [
			'success' => false,
			'error'   => [
				'code'    => 'activation_error',
				'message' => esc_html( $e->getMessage() )
			]
		] );
	}

	private function verifyAdminNonce() {
		$nonce = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : false;
		if ( ! $nonce ) {
			throw new \Exception( __( 'Requests require a nonce parameter.', 'paidcommunities' ) );
		}
		$result = \wp_verify_nonce( $nonce, "{$this->config->getPluginBasename()}-action" );
		if ( ! $result ) {
			throw new \Exception( __( 'Unauthorized request - nonce verification failed.', 'paidcommunities' ), 403 );
		}
	}

}