<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Settings_Repository {

	const STAMP_API_KEY = 'stamp_ic_wc_stamp_api_key';

	public function get_options(): array {
		return array(
			static::STAMP_API_KEY,
		);
	}

	public function get( $option, $default = null ) {
		$this->check_option( $option );
		return get_option( $option, $default );
	}

	public function set( $option, $value, $delete = false ): bool {

		$this->check_option( $option );

		if( $delete === true && is_null( $value ) ) {
			return delete_option( $option );
		}

		return update_option( $option, $value );
	}

	public function check_option( $option, $quiet = false ): bool {

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
