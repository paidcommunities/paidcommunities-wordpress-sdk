<?php

namespace PaidCommunities\WordPress\Service;

interface ServiceInterface {

	public function request($method, $path);
}