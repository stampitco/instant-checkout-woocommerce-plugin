<div class="wrap">
	<h1>
		<?php _e( 'Instant Checkout', STAMP_IC_WC_TEXT_DOMAIN ) ?>
	</h1>

    <?php if( is_array( $notifications ) ): ?>

        <?php /* @var Stamp_IC_WC_Settings_Notification $notification */  ?>
        <?php foreach( $notifications as $notification ): ?>
            <div class="notice notice-<?php esc_attr( $notification->get_type() ); ?> is-dismissible">
                <p>
	                <?php esc_html( $notification->get_message() ); ?>
                </p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">Dismiss this notice.</span>
                </button>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

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
	                        <?php if( ! empty( $stamp_api_key ) ) : ?>
		                        <?php submit_button( __( 'Test credentials', STAMP_IC_WC_TEXT_DOMAIN ), 'primary', 'test_stamp_ic_wc_credentials' ); ?>
	                        <?php endif; ?>
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
			                    <?php _e( 'Related WooCommerce Settings:', STAMP_IC_WC_TEXT_DOMAIN ) ?>
                            </h2>
                        </th>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="stamp_related_wc_credentials_key_id">
			                    <?php _e( 'REST API Credentials', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                            </label>
                        </th>
                        <td>
                            <p>
                                <?php if( is_null( $wc_credentials_key_id ) ): ?>
	                                <?php _e( 'There are no related WC Credentials. Ones will be created automatically when you save the settings.', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                                <?php endif; ?>
                                <?php if( ! is_null( $wc_credentials_key_id ) ): ?>

                                    <?php $url = admin_url( 'admin.php?page=wc-settings&tab=advanced&section=keys&edit-key=' . $wc_credentials_key_id ); ?>

	                                <a href="<?php echo esc_url( $url ); ?>" target="_blank">
		                                <?php _e( 'Details', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                                    </a>

                                    <input id="stamp_related_wc_credentials_key_id"
                                           name="stamp_related_wc_credentials_key_id"
                                           type="hidden"
                                           value="<?php echo esc_attr( $wc_credentials_key_id ); ?>"
                                    >
                                <?php endif; ?>
                            </p>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="stamp_related_webhook_order_updated_id">
                                <?php _e( 'Order Update Webhook', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                            </label>
                        </th>
                        <td>
                            <p>
                                <?php if( is_null( $webhook_order_updated ) ): ?>
                                    <?php _e( 'There is no related order update webhook. One will be created automatically when you save the settings.', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                                <?php endif; ?>
                                <?php if( ! is_null( $webhook_order_updated ) ): ?>

                                    <?php $url = admin_url( 'admin.php?page=wc-settings&tab=advanced&section=webhooks&edit-webhook=' . $webhook_order_updated->get_id() ); ?>

                                    <a href="<?php echo esc_url( $url ); ?>" target="_blank">
                                        <?php _e( 'Details', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                                    </a>

                                    <input id="stamp_related_webhook_order_updated_id"
                                           name="stamp_related_webhook_order_updated_id"
                                           type="hidden"
                                           value="<?php echo esc_attr( $webhook_order_updated->get_id() ); ?>"
                                    >
                                <?php endif; ?>
                            </p>
                        </td>
                    </tr>
                    <tr class="form-field">
                        <th scope="row">
                            <label for="stamp_related_webhook_order_deleted_id">
                                <?php _e( 'Order Delete Webhook', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                            </label>
                        </th>
                        <td>
                            <p>
                                <?php if( is_null( $webhook_order_deleted ) ): ?>
                                    <?php _e( 'There is no related order delete webhook. One will be created automatically when you save the settings.', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                                <?php endif; ?>
                                <?php if( ! is_null( $webhook_order_deleted ) ): ?>

                                    <?php $url = admin_url( 'admin.php?page=wc-settings&tab=advanced&section=webhooks&edit-webhook=' . $webhook_order_deleted->get_id() ); ?>

                                    <a href="<?php echo esc_url( $url ); ?>" target="_blank">
                                        <?php _e( 'Details', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                                    </a>

                                    <input id="stamp_related_webhook_order_deleted_id"
                                           name="stamp_related_webhook_order_deleted_id"
                                           type="hidden"
                                           value="<?php echo esc_attr( $webhook_order_deleted->get_id() ); ?>"
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
			<?php submit_button( __( 'Save settings', STAMP_IC_WC_TEXT_DOMAIN ), 'primary', 'save_stamp_ic_wc_settings' ); ?>

		</form>
	</div>
</div>