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

	public function __construct() {}

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

		$key_id = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_CREDENTIALS_ID );

		if( empty( $key_id ) ) {

			$consumer_data = $this->create_keys( 'Stamp', $params[ 'user_id' ], 'read_write' );

			if( ! empty( $consumer_data[ 'key_id' ] ) ) {
				$this->settings_repository->set( Stamp_IC_WC_Settings_Repository::WC_CREDENTIALS_ID, $consumer_data[ 'key_id' ] );
			}

			$this->api_client->save_wc_credentials( array(
				'ConsumerKey' => $consumer_data[ 'consumer_key' ],
				'ConsumerSecret' => $consumer_data[ 'consumer_secret' ],
				'Platform' => 'WooCommerce',
				'PlatformVersion' => WC_VERSION,
				'PluginVersion' => STAMP_IC_WC_VERSION,
				'WebSiteUrl' => get_bloginfo( 'url' ),
			) );
		}
	}
}
