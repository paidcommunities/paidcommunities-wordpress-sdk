<?php

namespace PaidCommunities\WordPress\Model;

interface ModelFactoryInterface {

	function buildModel( $clazz, $response );

}