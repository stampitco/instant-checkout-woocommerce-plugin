<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="form-wrap">
	<form
		action="<?php echo esc_url( admin_url( 'options-general.php?' . http_build_query( array( 'page' => 'stamp-ic-wc', 'tab' => 'styling' ) ) ) ); ?>"
		id="stamp-ic-wc-styling-form"
		method="post"
		class="stamp-ic-wc-form"
	>
		<table class="form-table">
			<tbody>
				<tr class="form-field">
					<th colspan="2" style="padding-bottom: 0;">
						<h2>
							<?php _e( 'Button styling:', STAMP_IC_WC_TEXT_DOMAIN ) ?>
						</h2>
					</th>
				</tr>
				<tr class="form-field">
					<th scope="row">
						<label for="stamp_api_key">
							<?php _e( 'Color', STAMP_IC_WC_TEXT_DOMAIN ); ?>
						</label>
					</th>
					<td>
						<ul class="radio-list">
							<li class="radio-list-item">
								<label>
									<input type="radio"
                                           name="stamp_ic_button_color"
                                           value="f7e0e2"
                                           data-parsley-required
                                           data-parsley-errors-container="#stamp-ic-button-color-error-block"
										<?php if( $button_color === 'f7e0e2' && ! $is_custom_button_color ): echo 'checked'; endif; ?>
                                    >
									<?php _e( 'Pink', STAMP_IC_WC_TEXT_DOMAIN ) ?>
                                    <code>#f7e0e2</code>
								</label>
							</li>
							<li class="radio-list-item">
								<label>
									<input type="radio"
                                           name="stamp_ic_button_color"
                                           value="0a1b2e"
                                        <?php if( $button_color === '0a1b2e' && ! $is_custom_button_color ): echo 'checked'; endif; ?>
                                    >
									<?php _e( 'Blue', STAMP_IC_WC_TEXT_DOMAIN ) ?>
                                    <code>#0a1b2e</code>
								</label>
							</li>
							<li class="radio-list-item">
								<label>
									<input type="radio"
                                           name="stamp_ic_button_color"
                                           value="ff4040"
                                        <?php if( $button_color === 'ff4040' && ! $is_custom_button_color ): echo 'checked'; endif; ?>
                                    >
									<?php _e( 'Red', STAMP_IC_WC_TEXT_DOMAIN ) ?>
                                    <code>#ff4040</code>
								</label>
							</li>
							<li class="radio-list-item">
								<label>
									<input type="radio"
                                           name="stamp_ic_button_color"
                                           value="custom"
										<?php if( $is_custom_button_color ): echo 'checked'; endif; ?>
                                    >
									<?php _e( 'Custom', STAMP_IC_WC_TEXT_DOMAIN ) ?>
								</label>
							</li>
						</ul>
                        <div id="stamp-ic-button-color-error-block"></div>
                        <div id="stamp-ic-button-custom-color-container" class="<?php if( ! $is_custom_button_color ): echo 'hidden'; endif; ?>">
                            <input name="stamp_ic_button_custom_color" type="text" value="<?php if( $is_custom_button_color ): echo '#' . esc_attr( $button_color ); endif; ?>" />
                            <p>
		                        <?php _e( 'Please enter custom color: #ffffff', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                            </p>
                        </div>
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
							<?php _e( 'Styling:', STAMP_IC_WC_TEXT_DOMAIN ) ?>
                        </h2>
                    </th>
                </tr>
                <tr class="form-field">
                    <th scope="row">
                        <label for="stamp_ic_additional_css">
							<?php _e( 'Additional CSS', STAMP_IC_WC_TEXT_DOMAIN ); ?>
                        </label>
                    </th>
                    <td>
                        <textarea rows="20" name="stamp_ic_additional_css"><?php echo esc_textarea( $additional_css ); ?></textarea>
                        <p>
		                    <?php _e( 'Enter custom css styling.', STAMP_IC_WC_TEXT_DOMAIN ); ?>
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

		<?php wp_nonce_field( 'stamp_ic_wc_styling_nonce','stamp_ic_wc_styling_nonce' ); ?>
		<?php submit_button( __( 'Save settings', STAMP_IC_WC_TEXT_DOMAIN ), 'primary', 'save_stamp_ic_wc_styling' ); ?>

	</form>
</div>
