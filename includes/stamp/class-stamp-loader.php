<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Stamp_Loader extends Stamp_IC_WooCommerce_Abstract_Loader {

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
	 * @return Stamp_IC_WC_Stamp_Loader
	 */
	public function set_api_client( Stamp_IC_WC_Api_Client $api_client ): Stamp_IC_WC_Stamp_Loader {
		$this->api_client = $api_client;
		return $this;
	}

	public function run() {
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