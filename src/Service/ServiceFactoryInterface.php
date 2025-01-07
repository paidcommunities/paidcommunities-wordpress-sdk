<?php

namespace PaidCommunities\WordPress\Service;

interface ServiceFactoryInterface {

	function getService( $name, $clazz );
}