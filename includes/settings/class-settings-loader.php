<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Settings_Loader extends Stamp_IC_WooCommerce_Abstract_Loader {

	/* @var Stamp_IC_WC_Settings_Repository $settings_repository */
	protected $settings_repository;

	/**
	 * @return Stamp_IC_WC_Settings_Repository
	 */
	public function get_settings_repository() {
		return $this->settings_repository;
	}

	/**
	 * @param Stamp_IC_WC_Settings_Repository $settings_repository
	 *
	 * @return Stamp_IC_WC_Settings_Loader
	 */
	public function set_settings_repository( Stamp_IC_WC_Settings_Repository $settings_repository ) {
		$this->settings_repository = $settings_repository;
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
				$this->container->get( 'Stamp_IC_WC_Settings_Cli_Command' ),
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