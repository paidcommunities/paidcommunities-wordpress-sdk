# Changelog

## 0.0.5 - 2026-11-13

* Fixed - Deactivating a license no longer deletes the local license when the nonce or capability check fails. The license is only cleared when the API request fails.
* Fixed - PaidCommunities\WordPress\HttpClient\AbstractClient throws an exception for any non-2xx response, such as 405, 429 and 5xx errors.
* Fixed - PaidCommunities\WordPress\HttpClient\AbstractClient::get() passed the options as the request.
* Fixed - PaidCommunities\WordPress\Exception\ApiErrorException::factory always returns an exception, with a fallback message.
* Fixed - The plugin update check cache key includes the license secret.
* Fixed - PaidCommunities\WordPress\Assets\AssetsApi::register_script no longer discards the version argument.
* Fixed - PaidCommunities\WordPress\License::read() loads the last check date.
* Fixed - Typo in the license settings deactivate button.
* Added - PaidCommunities\WordPress\License stores the license's product IDs, available from getProductIds() and hasProductId(). For a parent product, these are the child products the customer purchased, so a plugin can enable features by tier. They're saved when the license is activated and kept current by the plugin update check.
* Updated - Added getParam and getErrorCode to PaidCommunities\WordPress\Exception\ApiErrorException.
* Updated - Removed the registration of the non-existent build/license-settings.js script.

## 0.0.4 - 2025-08-28

* Updated - Added the PaidCommunities\WordPress\Hooks class which ensures global actions and filters are only triggered once.
* Updated - Added the PaidCommunities\WordPress\Cache class which is used to store the response from plugin update checks.

## 0.0.3 - 2025-07-24

* Updated documented methods and bumped internal version of @paidcommunities-wp/components to 1.0.3

## 0.0.2 - 2025-04-02

* Update logic for API endpoints called in PaidCommunities\WordPress\UpdateController so plugin data is fetched
  regardless of license status. The plugin data is needed to render the plugin details window that's native to
  WordPress.

## 0.0.1 - 2025-01-07

- Initial version release