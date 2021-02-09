<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Settings_Notifications_Repository {

	const SETTINGS = 'stamp_ic_settings_notifications';

	public function get_keys(): array {
		return array(
			static::SETTINGS,
		);
	}

	public function get_types(): array {
		return array(
			Stamp_IC_WC_Settings_Notification::ERROR,
			Stamp_IC_WC_Settings_Notification::SUCCESS,
			Stamp_IC_WC_Settings_Notification::WARNING,
		);
	}

	public function add( $key, $type, $message ) {
		$this->check_key( $key );
		$this->check_type( $key );
	}

	public function get( $key, $type = null, $id = null ) {

		$this->check_key( $key );

		if( ! is_null( $type ) ) {
			$this->check_type( $key );
		}


	}

	public function check_key( $key, $quiet = false ): bool {

		if( ! in_array( $key, $this->get_keys() ) ) {

			if( ! $quiet ) {
				throw new InvalidArgumentException(
					sprintf(
						'Invalid key value: %s',
						$key
					)
				);
			}

			return false;
		}

		return true;
	}

	public function check_type( $type, $quiet = false ): bool {

		if( ! in_array( $type, $this->get_types() ) ) {

			if( ! $quiet ) {
				throw new InvalidArgumentException(
					sprintf(
						'Invalid type value: %s',
						$type
					)
				);
			}

			return false;
		}

		return true;
	}
}
