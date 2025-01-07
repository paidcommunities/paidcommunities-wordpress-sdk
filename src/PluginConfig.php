<?php

namespace PaidCommunities\WordPress;

use PaidCommunities\WordPress\HttpClient\AbstractClient;
use PaidCommunities\WordPress\Admin\AdminAjaxController;
use PaidCommunities\WordPress\Admin\AdminScripts;
use PaidCommunities\WordPress\Admin\LicenseSettings;
use PaidCommunities\WordPress\Admin\Templates;
use PaidCommunities\WordPress\Assets\AssetsApi;
use PaidCommunities\WordPress\HttpClient\WordPressClient;
use PaidCommunities\WordPress\Util\GeneralUtils;

class PluginConfig {

	private $pluginFile;

	private $pluginBasename;

	private $version;

	private $settings;

	private $license;

	private $ajaxController;

	private $updates;

	private $baseDir;

	private $environment;

	private $templates;

	/**
	 * @var AdminScripts
	 */
	private $adminScripts;

	private $optionName;

	/**
	 * @var @since 1.0.1
	 */
	private $productId;

	/**
	 * @param string $slug The name of the plugin
	 * @param string $version The current version of the plugin
	 * @param array $overrides array of optional overrides to customize the default behavior.
	 */
	public function __construct( $plugin_file, $product_id, $overrides = [] ) {
		$this->pluginFile     = $plugin_file;
		$this->productId      = $product_id;
		$this->pluginBasename = \plugin_basename( $plugin_file );
		$this->version        = GeneralUtils::parsePluginVersion( $plugin_file );
		$this->baseDir        = dirname( __DIR__ );
		$this->environment    = AbstractClient::PRODUCTION;

		$overrides = array_merge(
			[
				'template_path' => __DIR__ . '/Admin/Views/',
				'option_name'   => $this->productId . '_license_settings',
				'version'       => GeneralUtils::parsePluginVersion( $plugin_file )
			],
			$overrides
		);

		$this->version    = $overrides['version'];
		$this->templates  = new Templates( $overrides['template_path'] );
		$this->optionName = $overrides['option_name'];

		$this->initialize();
	}

	private function initialize() {
		$assets_api           = new AssetsApi( $this->baseDir, plugin_dir_url( __DIR__ ), $this->version );
		$this->settings       = new LicenseSettings( $this );
		$this->ajaxController = new AdminAjaxController( $this );
		$this->updates        = new UpdateController( $this );
		$this->adminScripts   = new AdminScripts( $this, $assets_api );

		$this->updates->initialize();

		if ( is_admin() ) {
			$this->adminScripts->initialize();
		}
	}

	public function environment( $environment ) {
		if ( ! \in_array( $environment, [ AbstractClient::SANDBOX, AbstractClient::PRODUCTION ] ) ) {
			throw new \Exception( sprintf( 'Invalid environment value. Accepted values are %1$s or %2$s', AbstractClient::SANDBOX, AbstractClient::PRODUCTION ) );
		}
		$this->environment = $environment;

		return $this;
	}

	public function getEnvironment() {
		return $this->environment;
	}

	public function getClient() {
		return new WordPressClient( $this->environment );
	}

	public function getVersion() {
		return $this->version;
	}

	public function getOptionPrefix() {
		return 'paidcommunities_';
	}

	public function getOptionName() {
		return $this->optionName;
	}

	/**
	 * @return LicenseSettings
	 */
	public function getLicenseSettings() {
		return $this->settings;
	}

	/**
	 * @return UpdateController
	 */
	public function getUpdateController() {
		return $this->updates;
	}

	public function getPluginSlug() {
		return $this->pluginBasename;
	}

	public function getPluginBasename() {
		return $this->pluginBasename;
	}

	public function getPluginFile() {
		return $this->pluginFile;
	}

	public function getProductId() {
		return $this->productId;
	}

	/**
	 * @return \PaidCommunities\WordPress\License
	 */
	public function getLicense() {
		if ( ! $this->license ) {
			$this->license = new License( $this->optionName );
			$this->license->read();
		}

		return $this->license;
	}

	public function getPluginData() {
		return [
			'basename'          => $this->getPluginBasename(),
			'formattedBasename' => GeneralUtils::formatPluginName( $this->getPluginBasename() ),
			'nonce'             => GeneralUtils::createNonce( $this->getPluginBasename() ),
			'license'           => [
				'status'      => $this->getLicense()->getStatus(),
				'domain'      => $this->getLicense()->getDomain(),
				'domain_id'   => $this->getLicense()->getDomainId(),
				'registered'  => $this->getLicense()->isRegistered(),
				'license_key' => $this->getLicense()->getLicenseKey()
			],
			'i18n'              => [
				'activateLicense'      => __( 'Activate License', 'paidcommunities' ),
				'deactivateLicense'    => __( 'Deactivate License', 'paidcommunities' ),
				'licenseKey'           => __( 'License Key', 'paidcommunities' ),
				'activateMsg'          => __( 'Activating...', 'paidcommunities' ),
				'deactivateMsg'        => __( 'Deactivating...', 'paidcommunities' ),
				'activation_error'     => __( 'Activation Error!', 'paidcommunities' ),
				'activation_success'   => __( 'Activation Success!', 'paidcommunities' ),
				'deactivation_success' => __( 'De-activation Success!', 'paidcommunities' ),
				'general_error'        => __( 'Activation Error!', 'paidcommunities' )
			]
		];
	}

	public function getTemplates() {
		return $this->templates;
	}

	/**
	 * @return Admi
	 */
	public function getAdminScripts() {
		return $this->adminScripts;
	}

}