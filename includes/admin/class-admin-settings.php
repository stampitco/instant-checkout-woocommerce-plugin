<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Admin_Settings {

	/* @var Stamp_IC_WC_Settings_Repository $settings_repository */
	protected $settings_repository;

	/* @var Stamp_IC_WC_Settings_Notifications_Repository $notifications_repository */
	protected $notifications_repository;

	/* @var Stamp_IC_WC_Api_Client $api_client */
	protected $api_client;

	/**
	 * @return Stamp_IC_WC_Settings_Repository
	 */
	public function get_settings_repository(): Stamp_IC_WC_Settings_Repository {
		return $this->settings_repository;
	}

	/**
	 * @param Stamp_IC_WC_Settings_Repository $settings_repository
	 *
	 * @return Stamp_IC_WC_Admin_Settings
	 */
	public function set_settings_repository( Stamp_IC_WC_Settings_Repository $settings_repository ): Stamp_IC_WC_Admin_Settings {
		$this->settings_repository = $settings_repository;
		return $this;
	}

	/**
	 * @return Stamp_IC_WC_Settings_Notifications_Repository
	 */
	public function get_notifications_repository(): Stamp_IC_WC_Settings_Notifications_Repository {
		return $this->notifications_repository;
	}

	/**
	 * @param Stamp_IC_WC_Settings_Notifications_Repository $notifications_repository
	 *
	 * @return Stamp_IC_WC_Admin_Settings
	 */
	public function set_notifications_repository( Stamp_IC_WC_Settings_Notifications_Repository $notifications_repository ): Stamp_IC_WC_Admin_Settings {
		$this->notifications_repository = $notifications_repository;
		return $this;
	}

	/**
	 * @return Stamp_IC_WC_Api_Client
	 */
	public function get_api_client(): Stamp_IC_WC_Api_Client {
		return $this->api_client;
	}

	/**
	 * @param Stamp_IC_WC_Api_Client $api_client
	 *
	 * @return Stamp_IC_WC_Admin_Settings
	 */
	public function set_api_client( Stamp_IC_WC_Api_Client $api_client ): Stamp_IC_WC_Admin_Settings {
		$this->api_client = $api_client;
		return $this;
	}

	public function register_admin_menu_items() {
		add_submenu_page(
			'options-general.php',
			__( 'Stamp Instant Checkout Settings', STAMP_IC_WC_TEXT_DOMAIN ),
			__( 'Instant Checkout', STAMP_IC_WC_TEXT_DOMAIN ),
			'manage_options',
			'stamp-ic-wc',
			array( $this, 'render' )
		);
	}

	public function save_settings() {

		if( empty( $_POST[ 'stamp_ic_wc_settings_nonce' ] ) || empty( $_POST[ 'save_stamp_ic_wc_settings' ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['stamp_ic_wc_settings_nonce'], 'stamp_ic_wc_settings_nonce' ) ) {
			wp_die( __( 'Cheatin&#8217; huh?', STAMP_IC_WC_TEXT_DOMAIN ) );
		}

		$stamp_api_key = ! empty( $_POST[ 'stamp_api_key' ] ) ? wc_clean( $_POST[ 'stamp_api_key' ] ) : null;
		$related_wc_credentials_key_id = ! empty( $_POST[ 'stamp_related_wc_credentials_key_id' ] ) ? wc_clean( $_POST[ 'stamp_related_wc_credentials_key_id' ] ) : null;
        $related_webhook_order_updated_id = ! empty( $_POST[ 'stamp_related_webhook_order_updated_id' ] ) ? wc_clean( $_POST[ 'stamp_related_webhook_order_updated_id' ] ) : null;
        $related_webhook_order_deleted_id = ! empty( $_POST[ 'stamp_related_webhook_order_deleted_id' ] ) ? wc_clean( $_POST[ 'stamp_related_webhook_order_deleted_id' ] ) : null;

		if( ! is_null( $stamp_api_key ) ) {
			$this->settings_repository->set( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY, $stamp_api_key );
		}

		if( is_null( $related_wc_credentials_key_id ) ) {
			$this->settings_repository->delete( Stamp_IC_WC_Settings_Repository::WC_CREDENTIALS_ID );
		}

        if( is_null( $related_webhook_order_updated_id ) ) {
            $this->settings_repository->delete( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_UPDATED_ID );
        }

        if( is_null( $related_webhook_order_deleted_id ) ) {
            $this->settings_repository->delete( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_DELETED_ID );
        }

		$settings = array(
			'stamp_api_key' => $stamp_api_key,
			'related_wc_credentials_key_id' => $related_wc_credentials_key_id,
			'related_webhook_order_updated_id' => $related_webhook_order_updated_id,
			'related_webhook_order_deleted_id' => $related_webhook_order_deleted_id,
			'user_id' => get_current_user_id(),
		);

		do_action( 'stamp_ic_wc_settings_saved', $settings );

		$this->get_notifications_repository()->add(
			Stamp_IC_WC_Settings_Notifications_Repository::SETTINGS,
			Stamp_IC_WC_Settings_Notification::SUCCESS,
			__( 'Plugin settings were saved successfully', STAMP_IC_WC_TEXT_DOMAIN )
		);
	}

	public function test_stamp_api_credentials() {

		if( empty( $_POST[ 'stamp_ic_wc_settings_nonce' ] ) || empty( $_POST[ 'test_stamp_ic_wc_credentials' ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['stamp_ic_wc_settings_nonce'], 'stamp_ic_wc_settings_nonce' ) ) {
			wp_die( __( 'Cheatin&#8217; huh?', STAMP_IC_WC_TEXT_DOMAIN ) );
		}

		$result = $this->get_api_client()->verify();

		if( ! empty( $result[ 'error' ] ) ) {
			$this->get_notifications_repository()->add(
				Stamp_IC_WC_Settings_Notifications_Repository::SETTINGS,
				Stamp_IC_WC_Settings_Notification::ERROR,
				sprintf(
					__( 'Failed to verify Stamp API credentials. Error: %s. Code: %d', STAMP_IC_WC_TEXT_DOMAIN ),
					$result[ 'message' ],
					$result[ 'code' ]
				)
			);
		}

		if( empty( $result[ 'error' ] ) ) {

			$message = __( 'Stamp API credentials were successfully verified.', STAMP_IC_WC_TEXT_DOMAIN );

			if( is_array( $result ) ) {
				foreach ( $result as $param => $value ) {
					$message .= sprintf( ' %s: %s.', $param, $value );
				}
			}

			$this->get_notifications_repository()->add(
				Stamp_IC_WC_Settings_Notifications_Repository::SETTINGS,
				Stamp_IC_WC_Settings_Notification::SUCCESS,
				$message
			);
		}
	}

	public function render() {

		$stamp_api_key = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY );

		$wc_credentials_key_id = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_CREDENTIALS_ID );

		if( ! empty( $wc_credentials_key_id ) ) {
			global $wpdb;
			$wc_credentials_key_id = $wpdb->get_var( $wpdb->prepare( "SELECT key_id FROM {$wpdb->prefix}woocommerce_api_keys WHERE key_id = %d", $wc_credentials_key_id ) );
			if( empty( $wc_credentials_key_id ) ) {
                $wc_credentials_key_id = null;
            }
		}

        $webhook_order_updated = null;

        try {
            $webhook_order_updated = wc_get_webhook(
                $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_UPDATED_ID )
            );
        } catch (Exception $exception) {

        }

        $webhook_order_deleted = null;

		try {
            $webhook_order_deleted = wc_get_webhook(
                $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_DELETED_ID )
            );
        } catch (Exception $exception) {

        }

        $notifications = $this->get_notifications_repository()->get_all( Stamp_IC_WC_Settings_Notifications_Repository::SETTINGS );

		include __DIR__ . '/views/html-settings.php';
	}

	public function add_settings_link( array $links ): array {

		$settings_link = sprintf(
			'<a href="%s?%s">%s</a>',
			admin_url( 'options-general.php' ),
			http_build_query( array(
				'page' => 'stamp-ic-wc',
			) ),
			__( 'Settings', STAMP_IC_WC_TEXT_DOMAIN )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}
}
