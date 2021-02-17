<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Credentials extends WC_Auth {

	/* @var Stamp_IC_WC_Api_Client $api_client */
	protected $api_client;

	/* @var Stamp_IC_WC_Settings_Repository $settings_repository */
	protected $settings_repository;

	/* @var Stamp_IC_WC_Settings_Notifications_Repository $notifications_repository */
	protected $notifications_repository;

	public function __construct() {}

	/**
	 * @return Stamp_IC_WC_Settings_Notifications_Repository
	 */
	public function get_notifications_repository(): Stamp_IC_WC_Settings_Notifications_Repository {
		return $this->notifications_repository;
	}

	/**
	 * @param Stamp_IC_WC_Settings_Notifications_Repository $notifications_repository
	 *
	 * @return Stamp_IC_WC_Credentials
	 */
	public function set_notifications_repository( Stamp_IC_WC_Settings_Notifications_Repository $notifications_repository ): Stamp_IC_WC_Credentials {
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
	 * @return Stamp_IC_WC_Credentials
	 */
	public function set_api_client( Stamp_IC_WC_Api_Client $api_client ): Stamp_IC_WC_Credentials {
		$this->api_client = $api_client;
		return $this;
	}

	/**
	 * @return Stamp_IC_WC_Settings_Repository
	 */
	public function get_settings_repository(): Stamp_IC_WC_Settings_Repository {
		return $this->settings_repository;
	}

	/**
	 * @param Stamp_IC_WC_Settings_Repository $settings_repository
	 *
	 * @return Stamp_IC_WC_Credentials
	 */
	public function set_settings_repository( Stamp_IC_WC_Settings_Repository $settings_repository ): Stamp_IC_WC_Credentials {
		$this->settings_repository = $settings_repository;
		return $this;
	}

	public function save_wc_credentials( array $params ) {

	    if( ! empty( $params[ 'user_id' ] ) && array_key_exists( 'related_wc_credentials_key_id', $params ) ) {

            $key_id = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_CREDENTIALS_ID );

            if( empty( $key_id ) || (int) $key_id !== (int) $params[ 'related_wc_credentials_key_id' ] ) {

                $consumer_data = $this->create_keys( 'Instant Checkout', $params[ 'user_id' ], 'read_write' );

                if( ! empty( $consumer_data[ 'key_id' ] ) ) {
                    $this->settings_repository->set( Stamp_IC_WC_Settings_Repository::WC_CREDENTIALS_ID, $consumer_data[ 'key_id' ] );
                }

	            if( ! empty( $params[ 'stamp_api_key' ] ) ) {
		            $this->api_client->set_api_token( $params[ 'stamp_api_key' ] );
	            }

                $result = $this->api_client->save_wc_credentials( array(
                    'ConsumerKey' => $consumer_data[ 'consumer_key' ],
                    'ConsumerSecret' => $consumer_data[ 'consumer_secret' ],
                    'Platform' => 'WooCommerce',
                    'PlatformVersion' => WC_VERSION,
                    'PluginVersion' => STAMP_IC_WC_VERSION,
                    'WebSiteUrl' => get_bloginfo( 'url' ),
                    'StoreName' => get_bloginfo( 'name' ),
                ) );

                if( ! empty( $result[ 'error' ] ) ) {
                	$this->get_notifications_repository()->add(
		                Stamp_IC_WC_Settings_Notifications_Repository::SETTINGS,
		                Stamp_IC_WC_Settings_Notification::ERROR,
		                sprintf(
			                __( 'Failed to save WooCommerce credentials in the Stamp API. Error: %s. Code: %d', STAMP_IC_WC_TEXT_DOMAIN ),
			                $result[ 'message' ],
			                $result[ 'code' ]
		                )
	                );
                }

	            if( empty( $result[ 'error' ] ) ) {
		            $this->get_notifications_repository()->add(
			            Stamp_IC_WC_Settings_Notifications_Repository::SETTINGS,
			            Stamp_IC_WC_Settings_Notification::SUCCESS,
			            __( 'WooCommerce credentials were successfully saved in the Stamp API.', STAMP_IC_WC_TEXT_DOMAIN )
		            );
	            }
            }
        }
	}
}
