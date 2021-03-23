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
	public function get_wc_credentials(): Stamp_IC_WC_Credentials {
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

		add_action( 'stamp_ic_wc_settings_saved', array( $this->wc_credentials, 'save_wc_credentials' ) );
		add_action( 'stamp_ic_wc_settings_saved', array( $this->wc_webhooks, 'save_wc_webhooks' ) );

		add_filter( 'woocommerce_webhook_http_args', array( $this->wc_webhooks, 'process_webhook_http_params' ), 10, 3 );

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$this->register_cli_commands();
		}
	}

	public function register_cli_commands() {

		$this->set_commands(
			array(
				$this->container->get( 'Stamp_IC_WC_Api_Cli_Command' ),
			)
		);

		/* @var Stamp_IC_WooCommerce_Abstract_Cli_Command $command */
		foreach ( $this->get_commands() as $command ) {
			\WP_CLI::add_command(
				sprintf( '%s %s', $command->namespace(), $command->name() ),
				array( $command, 'run' ),
				$command->definition()
			);
		}
	}
}