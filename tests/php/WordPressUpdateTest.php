<?php

namespace php;

use PaidCommunities\WordPress\HttpClient\WordPressClient;
use PaidCommunities\WordPress\License;

class WordPressUpdateTest extends \PHPUnit\Framework\TestCase {

	public function testUpdateCheck() {
		$client = new WordPressClient();

		$domain = $client->domains->register( [
			'key'        => $_ENV['LICENSE_KEY'],
			'domain'     => $_ENV['DOMAIN'],
			'version'    => '3.2.58',
			'product_id' => 'prd_80jidOmdFitybAHD',
			'metadata'   => []
		] );

		$license = new License( 'premium-test-plugin', 'paidcommunities_' );
		$license->setSecret( $domain->secret );
		$license->setStatus( 'active' );
		$license->setDomainId( $domain->id );
		$license->setDomain( $domain->domain );
		$license->save();

		delete_site_transient( 'update_plugins' );
		wp_update_plugins();

		$client->setSecret( $domain->secret );
		$client->domains->delete( $domain->id );

		$updates = get_site_transient( 'update_plugins' );
		$this->assertIsObject( $updates, 'Plugin updates is an array' );
		if ( $updates->response ) {
			if ( isset( $updates->response['premium-test-plugin'] ) ) {
				$update = $updates->response['premium-test-plugin'];
				$this->assertIsObject( $update, 'Plugin update is an array' );
				$this->assertObjectHasAttribute( 'package', $update, 'Package: ' . $update->package );
			}
		} else {
			$this->assertArrayHasKey( PREMIUM_PLUGIN_NAME, $updates->no_update, 'Plugin does not have an update' );
		}
	}
}