<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Settings_Repository {

	const STAMP_API_KEY = 'stamp_ic_wc_stamp_api_key';

	const WC_CREDENTIALS_ID = 'stamp_ic_wc_credentials_id';

	const WC_WEBHOOK_ORDER_UPDATED_ID = 'stamp_ic_wc_webhook_order_updated_id';

	const WC_WEBHOOK_ORDER_DELETED_ID = 'stamp_ic_wc_webhook_order_deleted_id';

	const WC_CHECKOUT_BUTTON_COLOR = 'stamp_ic_wc_checkout_button_color';

	const ADDITIONAL_CSS = 'stamp_ic_wc_checkout_additional_css';

	public function get_options() {
		return array(
			static::STAMP_API_KEY,
			static::WC_CREDENTIALS_ID,
			static::WC_WEBHOOK_ORDER_UPDATED_ID,
			static::WC_WEBHOOK_ORDER_DELETED_ID,
			static::WC_CHECKOUT_BUTTON_COLOR,
			static::ADDITIONAL_CSS,
		);
	}

	public function get( $option, $default = null ) {
		$this->check_option( $option );
		return get_option( $option, $default );
	}

	public function set( $option, $value, $delete = false ) {

		$this->check_option( $option );

		if( $delete === true && is_null( $value ) ) {
			return delete_option( $option );
		}

		return update_option( $option, $value );
	}

	public function delete( $option ) {
		return $this->set( $option, null, true );
	}

	public function check_option( $option, $quiet = false ) {

		if( ! in_array( $option, $this->get_options() ) ) {

			if( ! $quiet ) {
				throw new InvalidArgumentException(
					sprintf(
						'Invalid option value: %s',
						$option
					)
				);
			}

			return false;
		}

		return true;
	}
}
