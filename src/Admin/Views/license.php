<div class="PaidCommunitiesLicense-settings">
    <div class="PaidCommunitiesGrid-root">
        <div class="PaidCommunitiesGrid-item">
            <div class="PaidCommunitiesStack-root LicenseKeyOptionGroup">
                <label class="PaidCommunitiesLabel-root"><?php esc_html_e( 'License Key', 'paidcommunities' ) ?></label>
                <div class="PaidCommunitiesInputBase-root LicenseStatus-<?php echo esc_attr($license->getStatus()) ?>">
					<?php if ( $license->isRegistered() ): ?>
                        <input id="<?php echo esc_attr( $name ) ?>-license_key" class="PaidCommunitiesInput-text LicenseKey" type="text" disabled value="<?php echo esc_attr( $license->getLicenseKey() ) ?>"/>
					<?php else: ?>
                        <input id="<?php echo esc_attr( $name ) ?>-license_key" class="PaidCommunitiesInput-text LicenseKey" type="text"/>
					<?php endif ?>
                </div>
				<?php if ( $license->isRegistered() ): ?>
                    <button class="button PaidCommunitiesButton-root DeactivateLicense" data-paidcommunities-props="<?php echo $data ?>"><?php esc_html_e( 'Deacticate License', 'paidcommunities' ) ?></button>
				<?php else: ?>
                    <button class="button PaidCommunitiesButton-root ActivateLicense" data-paidcommunities-props="<?php echo $data ?>"><?php esc_html_e( 'Activate License', 'paidcommunities' ) ?></button>
				<?php endif ?>
            </div>
        </div>
    </div>
</div>