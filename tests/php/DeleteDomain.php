<?php

namespace php;

use PaidCommunities\HttpClient\AbstractClient;
use PaidCommunities\WordPress\HttpClient\WordPressClient;
use PHPUnit\Framework\TestCase;

class DeleteDomain extends TestCase {

	public function testDeleteDomain() {
		$client = new WordPressClient( AbstractClient::SANDBOX, 'sk_dXUC62AKBn5oAt4wPfwf1TC0VyRIcLr5' );
		try {
			$response = $client->domains->delete( $_ENV['DOMAIN_ID'] );

			$this->assertIsString( $response->id, sprintf( 'Domain ID: %s', $response->id ) );

		} catch ( \Exception $e ) {
			$this->fail( 'Domain deletion failed. Reason: ' . $e->getMessage() );
		}
	}
}