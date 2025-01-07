<?php
echo $_ENV['WORDPRESS_DIR'];
// load WordPress
include_once $_ENV['WORDPRESS_DIR'] . '/index.php';

include_once dirname( __DIR__ ) . '/vendor/autoload.php';

