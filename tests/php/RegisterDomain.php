<?php

namespace php;

use PaidCommunities\WordPress\HttpClient\WordPressClient;
use PHPUnit\Framework\TestCase;

class RegisterDomain extends TestCase {

	public function testRegisterDomain() {
		$client = new WordPressClient();
		try {
			$response = $client->domains->register( [
				'key'        => $_ENV['LICENSE_KEY'],
				'domain'     => $_ENV['DOMAIN'],
				'version'    => '3.2.58',
				'product_id' => 'prd_80jidOmdFitybAHD',
				'metadata'   => []
			] );
			$this->assertIsString( $response->id, sprintf( 'Domain ID: %s', $response->id ) );

			$client->setSecret( $response->secret );

			$response = $client->domains->delete( $response->id );
			$this->assertIsString( $response->id, sprintf( 'Domain %s deleted.', $response->id ) );
		} catch ( \Exception $e ) {
			$this->fail( 'Domain registration failed. Reason: ' . $e->getMessage() );
		}
	}
}