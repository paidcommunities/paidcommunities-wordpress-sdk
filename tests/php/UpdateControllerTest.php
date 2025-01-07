<?php

namespace php;

use PaidCommunities\WordPress\HttpClient\WordPressClient;
use PaidCommunities\WordPress\PluginConfig;

class UpdateControllerTest extends \PHPUnit\Framework\TestCase {

	public function testUpdateController() {
		$plugin = new PluginConfig( 'premium-test-plugin', '1.0.0' );
		$client = new WordPressClient();

		$domain = $client->domainRegistration->register( $_ENV['LICENSE'], [ 'domain' => $_ENV['DOMAIN'] ] );

		$client->setSecret( $domain->secret );

		$license = $plugin->getLicense();
		$license->setDomainId( $domain->id );
		$license->setDomain( $domain->domain );
		$license->setSecret( $domain->secret );
		$license->setStatus( 'active' );
		$license->save();


		$response = $plugin->getUpdateController()->checkPluginUpdates( [], [ 'Version' => '1.0.0' ], null );
		$this->assertIsArray( $response, print_r( $response ) );

		try {
			$client->domains->delete( $domain->id );
		} catch ( \Exception $e ) {
			$license->delete();
		}
	}
}