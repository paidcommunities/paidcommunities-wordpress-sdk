<?php

namespace php;

class SanitizeUrlTest extends \PHPUnit\Framework\TestCase {

	public function testSanitizeUrl() {
		$result = wp_parse_url( sanitize_url( 'https://paidcommunities.com' ), PHP_URL_HOST );
		$this->assertIsString( $result, sprintf( 'Hostname: %s', $result ) );
	}
}