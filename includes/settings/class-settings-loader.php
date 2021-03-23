<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Settings_Loader extends Stamp_IC_WooCommerce_Abstract_Loader {

	/* @var Stamp_IC_WC_Settings_Repository $settings_repository */
	protected $settings_repository;

	public function init() {
		$settings_repository = new Stamp_IC_WC_Settings_Repository();
		$this->set_settings_repository( $settings_repository );
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$settings_cli_command = new Stamp_IC_WC_Settings_Cli_Command();
			$settings_cli_command->set_settings_repository( $settings_repository );
			$this->set_commands( array(
				$settings_cli_command
			) );
		}
	}

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

	}
}