<?php

namespace PaidCommunities\WordPress\Admin;

class Templates {

	private $template_path;

	private $default_path;

	public function __construct( $template_path ) {
		$this->template_path = $template_path;
		$this->default_path  = dirname( __FILE__ ) . '/Views/';
	}

	public function loadTemplate( $template_name, $args = [] ) {
		$template = $this->locateTemplate( $template_name );
		if ( file_exists( $template ) ) {
			extract( $args );
			include $template;
		}
	}

	public function locateTemplate( $template_name ) {
		$template = trailingslashit( $this->template_path ) . $template_name;
		if ( ! file_exists( $template ) ) {
			$template = trailingslashit( $this->default_path ) . $template_name;
		}

		return $template;
	}
}