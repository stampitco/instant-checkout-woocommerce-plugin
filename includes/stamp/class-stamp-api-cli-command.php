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
		return 'api';
	}

	public function short_description(): string {
		return 'Save plugin settings';
	}

	public function synopsis(): array {
		return array(

		);
	}

	public function run( $args, $assoc_args ) {

	}
}
