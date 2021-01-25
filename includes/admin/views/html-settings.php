<div class="wrap">
	<h1>
		<?php _e( 'Instant Checkout', STAMP_IC_WC_TEXT_DOMAIN ) ?>
	</h1>
	<div class="form-wrap">
		<form
			action="<?php echo esc_url( admin_url( 'options-general.php?' . http_build_query( array( 'page' => 'stamp-ic-wc' ) ) ) ); ?>"
			id="stamp-ic-wc-settings-form"
			method="post"
		>
			<table class="form-table">
				<tbody>
                    <tr class="form-field">
                        <th colspan="2" style="padding-bottom: 0;">
                            <h2>
                                <?php _e( 'Stamp API Settings:', STAMP_IC_WC_TEXT_DOMAIN ) ?>
                            </h2>
                        </th>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="stamp_api_key">
                                <?php _e( 'API url', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                            </label>
                        </th>
                        <td>
                            <input id="stamp_api_url"
                                   name="stamp_api_url"
                                   type="url"
                                   data-parsley-type="url"
                                   required
                                   value="<?php echo esc_attr( $stamp_api_url ); ?>"
                            >
                            <p>
                                <?php _e( 'The Stamp API url', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                            </p>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="stamp_api_key">
                                <?php _e( 'API Key', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                            </label>
                        </th>
                        <td>
                            <input id="stamp_api_key"
                                   name="stamp_api_key"
                                   type="text"
                                   required
                                   value="<?php echo esc_attr( $stamp_api_key ); ?>"
                            >
                            <p>
                                <?php _e( 'The api key used to authenticate against the Stamp API', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                            </p>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row" colspan="2">
                            <hr>
                        </th>
                    </tr>
                    <tr class="form-field">
                        <th colspan="2" style="padding-bottom: 0;padding-top: 0;">
                            <h2>
			                    <?php _e( 'Related WooCommerce Credentials:', STAMP_IC_WC_TEXT_DOMAIN ) ?>
                            </h2>
                        </th>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="stamp_api_key">
			                    <?php _e( 'API Key', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                            </label>
                        </th>
                        <td>
                            <p>
                                <?php if( is_null( $wc_credentials_key_id ) ): ?>
	                                <?php _e( 'There are no related WC Credentials. Ones will be created automatically when you save the settings.', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                                <?php endif; ?>
                                <?php if( ! is_null( $wc_credentials_key_id ) ): ?>

                                    <?php $url = admin_url( 'admin.php?page=wc-settings&tab=advanced&section=keys&edit-key=' . $wc_credentials_key_id ); ?>

	                                <a href="<?php echo esc_url( $url ); ?>">
		                                <?php _e( 'Details', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                                    </a>

                                    <input id="stamp_related_wc_credentials_key_id"
                                           name="stamp_related_wc_credentials_key_id"
                                           type="hidden"
                                           required
                                           value="<?php echo esc_attr( $wc_credentials_key_id ); ?>"
                                    >
                                <?php endif; ?>
                            </p>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row" colspan="2">
                            <hr>
                        </th>
                    </tr>
				</tbody>
			</table>
			<?php wp_nonce_field( 'stamp_ic_wc_settings_nonce','stamp_ic_wc_settings_nonce' ); ?>
			<?php submit_button( __( 'Save', STAMP_IC_WC_TEXT_DOMAIN ), 'primary', 'submit' ); ?>
		</form>
	</div>
</div>