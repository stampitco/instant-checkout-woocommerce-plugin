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
			array(
				'description' => 'Stamp API url',
				'type' => 'assoc',
				'name' => 'stamp_api_url',
				'optional' => true,
			),
            array(
                'description' => 'WooCommerce Credentials ID',
                'type' => 'assoc',
                'name' => 'wc_credentials_id',
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

		if( ! empty( $assoc_args[ 'stamp_api_url' ] ) ) {

			$this->settings_repository->set(
				Stamp_IC_WC_Settings_Repository::STAMP_API_URL,
				$assoc_args[ 'stamp_api_url' ]
			);

			WP_CLI::success(
				sprintf(
					'Stamp API url option set: %s',
					$assoc_args[ 'stamp_api_url' ]
				)
			);
		}

        if( ! empty( $assoc_args[ 'wc_credentials_id' ] ) ) {

            $this->settings_repository->set(
                Stamp_IC_WC_Settings_Repository::WC_CREDENTIALS_ID,
                $assoc_args[ 'wc_credentials_id' ]
            );

            WP_CLI::success(
                sprintf(
                    'WooCommerce Credentials ID option set: %s',
                    $assoc_args[ 'wc_credentials_id' ]
                )
            );
        }

        if( ! empty( $assoc_args[ 'wc_webhooks_id' ] ) ) {

            $list = explode( ',', $assoc_args[ 'wc_webhooks_id' ] );

            if( ! is_array( $list ) ) {
                WP_CLI::error(
                    'WooCommerce Webhooks ID option must be a comma separated list of values'
                );
            }

            $this->settings_repository->set(
                Stamp_IC_WC_Settings_Repository::WC_WEBHOOKS_ID,
                $list
            );

            WP_CLI::success(
                sprintf(
                    'WooCommerce Webhooks ID option set: %s',
                    $assoc_args[ 'wc_webhooks_id' ]
                )
            );
        }
	}
}
