<?php

namespace PaidCommunities\WordPress\Model;

/**
 * @property string $slug
 * @property bool $update
 * @property string $version
 * @property string $package
 * @property string $last_check
 * @property array $icons
 * @property array $banners
 * @property string $tested
 * @property string $requires
 * @property string $requires_php
 */
class PluginUpdateResponse extends AbstractModel {
	/**
	 * @return bool
	 */
	public function isUpdate() {
		return $this->update;
	}

	/**
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @param string $slug
	 */
	public function setSlug( string $slug ) {
		$this->slug = $slug;
	}


	/**
	 * @param bool $update
	 */
	public function setUpdate( bool $update ) {
		$this->update = $update;
	}

	/**
	 * @return string
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @param string $version
	 */
	public function setVersion( string $version ) {
		$this->version = $version;
	}

	/**
	 * @return string
	 */
	public function getPackage() {
		return $this->package;
	}

	/**
	 * @param string $package
	 */
	public function setPackage( string $package ) {
		$this->package = $package;
	}

	/**
	 * @return string
	 */
	public function getLastCheck() {
		return $this->last_check;
	}

	/**
	 * @param string $lastCheck
	 */
	public function setLastCheck( string $lastCheck ) {
		$this->last_check = $lastCheck;
	}

	/**
	 * @return \stdClass
	 */
	public function getIcons() {
		return $this->icons;
	}

	/**
	 * @param \stdClass $icons
	 */
	public function setIcons( \stdClass $icons ) {
		$this->icons = $icons;
	}

	/**
	 * @return string
	 */
	public function getTested() {
		return $this->tested;
	}

	/**
	 * @param string $tested
	 */
	public function setTested( string $tested ) {
		$this->tested = $tested;
	}

	/**
	 * @return string
	 */
	public function getRequires() {
		return $this->requires;
	}

	/**
	 * @param string $requires
	 */
	public function setRequires( string $requires ) {
		$this->requires = $requires;
	}

	/**
	 * @return string
	 */
	public function getRequiresPhp() {
		return $this->requires_php;
	}

	/**
	 * @param string $requires_php
	 */
	public function setRequiresPhp( string $requires_php ) {
		$this->requires_php = $requires_php;
	}

	/**
	 * @return array
	 */
	public function getBanners() {
		return $this->banners;
	}

	/**
	 * @param array $banners
	 */
	public function setBanners( array $banners ) {
		$this->banners = $banners;
	}
}