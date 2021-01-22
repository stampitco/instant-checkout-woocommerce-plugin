<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) || ! defined( 'WP_CLI' ) ) {
	die;
}

class Stamp_IC_WC_Settings_Cli_Command extends Stamp_IC_WooCommerce_Abstract_Cli_Command {

	/* @var Stamp_IC_WC_Settings_Repository $settings_repository */
	protected $settings_repository;

	/**
	 * @return Stamp_IC_WC_Settings_Repository
	 */
	public function get_settings_repository(): Stamp_IC_WC_Settings_Repository {
		return $this->settings_repository;
	}

	/**
	 * @param Stamp_IC_WC_Settings_Repository $settings_repository
	 *
	 * @return Stamp_IC_WC_Settings_Cli_Command
	 */
	public function set_settings_repository( Stamp_IC_WC_Settings_Repository $settings_repository ): Stamp_IC_WC_Settings_Cli_Command {
		$this->settings_repository = $settings_repository;
		return $this;
	}

	public function name(): string {
		return 'settings';
	}

	public function short_description(): string {
		return 'Save plugin settings';
	}

	public function synopsis(): array {
		return array(
			array(
				'description' => 'Stamp API key',
				'type' => 'assoc',
				'name' => 'stamp_api_key',
				'optional' => true,
			),
		);
	}

	public function run( $args, $assoc_args ) {

		if( ! empty( $assoc_args[ 'stamp_api_key' ] ) ) {

			$this->settings_repository->set(
				Stamp_IC_WC_Settings_Repository::STAMP_API_KEY,
				$assoc_args[ 'stamp_api_key' ]
			);

			WP_CLI::success(
				sprintf(
					'Stamp API key option set: %s',
					$assoc_args[ 'stamp_api_key' ]
				)
			);
		}
	}
}
