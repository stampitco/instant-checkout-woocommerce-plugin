<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) || ! defined( 'WP_CLI' ) ) {
	die;
}

class Stamp_IC_WC_Api_Cli_Command extends Stamp_IC_WooCommerce_Abstract_Cli_Command {

	/* @var Stamp_IC_WC_Api_Client $api_client */
	protected $api_client;

	/**
	 * @return Stamp_IC_WC_Api_Client
	 */
	public function get_api_client(): Stamp_IC_WC_Api_Client {
		return $this->api_client;
	}

	/**
	 * @param Stamp_IC_WC_Api_Client $api_client
	 *
	 * @return Stamp_IC_WC_Api_Cli_Command
	 */
	public function set_api_client( Stamp_IC_WC_Api_Client $api_client ): Stamp_IC_WC_Api_Cli_Command {
		$this->api_client = $api_client;
		return $this;
	}

	public function name(): string {
		return 'stamp-api';
	}

	public function short_description(): string {
		return 'Interact with Stamp API from the cli';
	}

	public function synopsis(): array {
		return array(
			array(
				'description' => 'Which action to perform against the Stamp API',
				'type' => 'assoc',
				'name' => 'action',
				'optional' => false,
				'options' => array(
					'set_settings',
					'verify',
				),
			),
			array(
				'description' => 'WC consumer key',
				'type' => 'assoc',
				'name' => 'wc_consumer_key',
				'optional' => true,
			),
			array(
				'description' => 'WC consumer secret',
				'type' => 'assoc',
				'name' => 'wc_consumer_secret',
				'optional' => true,
			),
		);
	}

	public function run( $args, $assoc_args ) {
		if( $assoc_args[ 'action' ] === 'set_settings' ) {
			$this->set_settings( $args, $assoc_args );
		}
		if( $assoc_args[ 'action' ] === 'verify' ) {
			$this->verify( $args, $assoc_args );
		}
	}

	protected function set_settings( $args, $assoc_args ) {

		if( empty( $assoc_args[ 'wc_consumer_key' ] ) ) {
			WP_CLI::error(
				'Please provide wc_consumer_key option'
			);
		}

		if( empty( $assoc_args[ 'wc_consumer_secret' ] ) ) {
			WP_CLI::error(
				'Please provide wc_consumer_secret option'
			);
		}

		$result = $this->get_api_client()->save_wc_credentials( array(
			'ConsumerKey' => $assoc_args[ 'wc_consumer_key' ],
			'ConsumerSecret' => $assoc_args[ 'wc_consumer_secret' ],
			'Platform' => 'WooCommerce',
			'PlatformVersion' => WC_VERSION,
			'PluginVersion' => STAMP_IC_WC_VERSION,
			'WebSiteUrl' => get_bloginfo( 'url' ),
		) );

		if( is_array( $result ) && ! empty( $result[ 'error' ] ) ) {
			WP_CLI::error(
				sprintf(
					'Failed to save the settings. Error: %s. Code: %s',
					$result[ 'message' ],
					$result[ 'code' ]
				)
			);
		}

		WP_CLI::success( 'The settings were saved' );
	}

	protected function verify( $args, $assoc_args ) {

		$result = $this->get_api_client()->verify();

		if( ! empty( $result[ 'error' ] ) ) {
			WP_CLI::error(
				sprintf(
					__( 'Failed to verify connection to Stamp API: Error: %s. Code: %s', STAMP_IC_WC_TEXT_DOMAIN ),
					$result[ 'message' ],
					$result[ 'code' ]
				)
			);
		}

		WP_CLI::success(
			'Connection to Stamp API verified'
		);
	}
}
