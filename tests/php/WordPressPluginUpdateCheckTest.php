<?php

namespace php;

use PaidCommunities\WordPress\HttpClient\WordPressClient;
use PaidCommunities\WordPress\Model\PluginUpdateResponse;
use PHPUnit\Framework\TestCase;

class WordPressPluginUpdateCheckTest extends TestCase {

	public function testUpdateCheck() {
		$client = new WordPressClient();
		$client->setSecret('sk_aODIXhY7lzUGWOVm6qGPgPk4ci9iu05V');

		$response = $client->plugins->updateCheck( [
			'version'    => '3.2.58',
			'product_id' => 'prd_80jidOmdFitybAHD'
		] );

		return $this->assertInstanceOf( PluginUpdateResponse::class, $response, 'fuck' );
	}

}