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
							<?php esc_html_e( 'API Key', STAMP_IC_WC_TEXT_DOMAIN ); ?>
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
				</tbody>
			</table>
			<?php wp_nonce_field( 'stamp_ic_wc_settings_nonce','stamp_ic_wc_settings_nonce' ); ?>
			<?php submit_button( __( 'Save', STAMP_IC_WC_TEXT_DOMAIN ), 'primary', 'submit' ); ?>
		</form>
	</div>
</div>