<?php

namespace PaidCommunities\WordPress\Model;

/**
 * @property string $name
 * @property string $slug
 * @property array $tags
 * @property string $tested
 * @property array $banners
 * @property string $version
 * @property string requires
 * @property array $sections
 * @property array $contributors
 * @property string last_updated
 * @property string requires_php
 * @property string download_link
 */
class PluginInfoResponse extends AbstractModel {
	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;

		return $this;
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
	public function setSlug( $slug ) {
		$this->slug = $slug;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * @param array $tags
	 */
	public function setTags( $tags ) {
		$this->tags = $tags;

		return $this;
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
	public function setTested( $tested ) {
		$this->tested = $tested;

		return $this;
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
	public function setBanners( $banners ) {
		$this->banners = $banners;

		return $this;
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
	public function setVersion( $version ) {
		$this->version = $version;

		return $this;
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
	public function setRequires( $requires ) {
		$this->requires = $requires;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getSections() {
		return $this->sections;
	}

	/**
	 * @param array $sections
	 */
	public function setSections( $sections ) {
		$this->sections = $sections;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getContributors() {
		return $this->contributors;
	}

	/**
	 * @param array $contributors
	 */
	public function setContributors( $contributors ) {
		$this->contributors = $contributors;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLastUpdated() {
		return $this->last_updated;
	}

	/**
	 * @param string $last_updated
	 */
	public function setLastUpdated( $last_updated ) {
		$this->last_updated = $last_updated;

		return $this;
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
	public function setRequiresPhp( $requires_php ) {
		$this->requires_php = $requires_php;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDownloadLink() {
		return $this->download_link;
	}

	/**
	 * @param string $download_link
	 */
	public function setDownloadLink( $download_link ) {
		$this->download_link = $download_link;

		return $this;
	}

	public function transformBanners( $value ) {
		return (array) $value;
	}

	public function transformSections( $value ) {
		return (array) $value;
	}

	public function transformContributors( $value ) {
		if ( is_object( $value ) ) {
			$value = (array) $value;
			foreach ( $value as $username => $contributor_data ) {
				$value[ $username ] = (array) $contributor_data;
			}
		}

		return $value;
	}
}