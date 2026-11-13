# paidcommunities-wordpress-php
WordPress PHP SDK for PaidCommunities

## Setup

Create a `PluginConfig` in your plugin's main file with the plugin file and its PaidCommunities product ID.

```php
use PaidCommunities\WordPress\PluginConfig;

$config = new PluginConfig( __FILE__, 'prd_80jidOmdFitybAHD' );
```

If you sell your plugin in tiers, such as Basic, Pro and Agency, use the **parent product's ID**. The parent product holds
the plugin's releases, and a license for any of its child products can activate the plugin and receive updates.

## Enabling features by tier

The license stores the IDs of the products it was purchased for. If your plugin's product is a parent product, these are
the child products the customer bought, so your plugin can enable features based on the customer's tier.

```php
$license = $config->getLicense();

if ( $license->isActive() && $license->hasProductId( 'prd_pro_tier_id' ) ) {
	// enable the Pro features
}
```

Use `getProductIds()` to get every product ID on the license.

```php
$productIds = $config->getLicense()->getProductIds(); // ['prd_pro_tier_id']
```

The product IDs are saved when the license is activated, and kept up to date by the plugin update check. If a customer
changes tiers, the new product ID is available after WordPress next checks for plugin updates. Licenses activated before
version 0.0.5 receive their product IDs on their first update check after the SDK is updated.
