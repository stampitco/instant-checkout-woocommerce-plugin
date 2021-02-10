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

	public function add( $key, $type, $message ): Stamp_IC_WC_Settings_Notification {

		$this->check_key( $key );
		$this->check_type( $type );

		$notification = new Stamp_IC_WC_Settings_Notification(
			md5( $message . time() ),
			$type,
			$message
		);

		$notifications = wp_cache_get( $key );

		if( ! is_array( $notifications ) ) {
			$notifications = array();
		}

		if( ! array_key_exists( $type, $notifications ) ) {
			$notifications[ $type ] = array();
		}

		$notifications[ $type ][] = $notification->to_array();

		wp_cache_set( $key, $notifications );

		return $notification;
	}

	public function get_all( $key, $type = null ): array {

		$this->check_key( $key );

		$notifications = wp_cache_get( $key );

		if( ! is_array( $notifications ) ) {
			return array();
		}

		if( is_null( $type ) ) {

			$transformed = array();

			foreach ( $notifications as $type => $notifications_with_type ) {
				foreach ( $notifications_with_type as $notification ) {
					$transformed[] = new Stamp_IC_WC_Settings_Notification(
						$notification[ 'id' ],
						$notification[ 'type' ],
						$notification[ 'message' ]
					);
				}
			}

			return $transformed;
		}

		$this->check_type( $type );

		if( ! array_key_exists( $type, $notifications ) ) {
			return array();
		}

		return array_map( function( $notification ) {
			return new Stamp_IC_WC_Settings_Notification(
				$notification[ 'id' ],
				$notification[ 'type' ],
				$notification[ 'message' ]
			);
		}, $notifications[ $type ] );
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
