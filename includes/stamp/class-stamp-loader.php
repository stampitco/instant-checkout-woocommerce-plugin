<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Stamp_Loader extends Stamp_IC_WooCommerce_Abstract_Loader {

	/* @var Stamp_IC_WC_Api_Client $api_client */
	protected $api_client;

	/* @var Stamp_IC_WC_Credentials $wc_credentials */
	protected $wc_credentials;

    /* @var Stamp_IC_WC_Webhooks $wc_webhooks */
    protected $wc_webhooks;

	public function init() {
		$settings_repository = new Stamp_IC_WC_Settings_Repository();

		$api_client = new Stamp_IC_WC_Api_Client();
		$api_client->set_api_url( STAMP_API_URL );
		$api_client->set_api_token( $settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY ) );
		$this->set_api_client( $api_client );

		$wc_credentials = new Stamp_IC_WC_Credentials();
		$wc_credentials->set_settings_repository( $settings_repository );
		$wc_credentials->set_api_client( $api_client );
		$wc_credentials->set_notifications_repository( new Stamp_IC_WC_Settings_Notifications_Repository() );
		$this->set_wc_credentials( $wc_credentials );

		$wc_webhooks = new Stamp_IC_WC_Webhooks();
		$wc_webhooks->set_settings_repository( $settings_repository );
		$this->set_wc_webhooks( $wc_webhooks );

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$stamp_api_cli_command = new Stamp_IC_WC_Api_Cli_Command();
			$stamp_api_cli_command->set_api_client( $api_client );
			$this->set_commands( array(
				$stamp_api_cli_command
			) );
		}
	}

	/**
	 * @return Stamp_IC_WC_Api_Client
	 */
	public function get_api_client() {
		return $this->api_client;
	}

	/**
	 * @param Stamp_IC_WC_Api_Client $api_client
	 *
	 * @return Stamp_IC_WC_Stamp_Loader
	 */
	public function set_api_client( Stamp_IC_WC_Api_Client $api_client ) {
		$this->api_client = $api_client;
		return $this;
	}

	/**
	 * @return Stamp_IC_WC_Credentials
	 */
	public function get_wc_credentials() {
		return $this->wc_credentials;
	}

	/**
	 * @param Stamp_IC_WC_Credentials $wc_credentials
	 *
	 * @return Stamp_IC_WC_Stamp_Loader
	 */
	public function set_wc_credentials( Stamp_IC_WC_Credentials $wc_credentials ) {
		$this->wc_credentials = $wc_credentials;
		return $this;
	}

    /**
     * @return Stamp_IC_WC_Webhooks
     */
    public function get_wc_webhooks(): Stamp_IC_WC_Webhooks {
        return $this->wc_webhooks;
    }

    /**
     * @param Stamp_IC_WC_Webhooks $wc_webhooks
     * @return Stamp_IC_WC_Stamp_Loader
     */
    public function set_wc_webhooks( Stamp_IC_WC_Webhooks $wc_webhooks ) {
        $this->wc_webhooks = $wc_webhooks;
        return $this;
    }

	public function run() {
		add_action( 'stamp_ic_wc_settings_saved', array( $this->get_wc_credentials(), 'save_wc_credentials' ) );
		add_action( 'stamp_ic_wc_settings_saved', array( $this->get_wc_webhooks(), 'save_wc_webhooks' ) );
		add_filter( 'woocommerce_webhook_http_args', array( $this->get_wc_webhooks(), 'process_webhook_http_params' ), 10, 3 );
	}
}