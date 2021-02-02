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
                'description' => 'WooCommerce Credentials ID',
                'type' => 'assoc',
                'name' => 'wc_credentials_id',
                'optional' => true,
            ),
            array(
                'description' => 'WooCommerce Order Updated Webhook ID',
                'type' => 'assoc',
                'name' => 'wc_webhook_order_updated_id',
                'optional' => true,
            ),
            array(
                'description' => 'WooCommerce Order Deleted Webhook ID',
                'type' => 'assoc',
                'name' => 'stamp_ic_wc_webhook_order_deleted_id',
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

        if( isset( $assoc_args[ 'wc_credentials_id' ] ) ) {

            if( $assoc_args[ 'wc_credentials_id' ] === 'delete' ) {

                $this->settings_repository->delete( Stamp_IC_WC_Settings_Repository::WC_CREDENTIALS_ID );

                WP_CLI::success(
                    'WooCommerce Credentials ID option removed'
                );
            }

            if( $assoc_args[ 'wc_credentials_id' ] !== 'delete' ) {

                global $wpdb;

                $check = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT key_id FROM {$wpdb->prefix}woocommerce_api_keys WHERE key_id = %d",
                        $assoc_args[ 'wc_credentials_id' ]
                    )
                );

                if( ! $check ) {
                    WP_CLI::error(
                        sprintf(
                            'Provided WooCommerce Credentials ID do not exists: %s',
                            $assoc_args[ 'wc_credentials_id' ]
                        )
                    );
                }

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
        }

        if( ! empty( $assoc_args[ 'wc_webhook_order_updated_id' ] ) ) {

            if( $assoc_args[ 'wc_webhook_order_updated_id' ] === 'delete' ) {

                $this->settings_repository->delete( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_UPDATED_ID );

                WP_CLI::success(
                    'WooCommerce Order Updated Webhook ID option removed'
                );
            }

            if( $assoc_args[ 'wc_webhook_order_updated_id' ] !== 'delete' ) {

                $webhook = wc_get_webhook( $assoc_args[ 'wc_webhook_order_updated_id' ] );

                if( $webhook instanceof WC_Webhook || $webhook->get_topic() !== 'order.updated' ) {
                    WP_CLI::error(
                        sprintf(
                            'Provided WooCommerce Order Updated Webhook ID: %s do not exists or it has invalid topic ',
                            $assoc_args[ 'wc_webhook_order_updated_id' ]
                        )
                    );
                }

                $this->settings_repository->set(
                    Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_UPDATED_ID,
                    $assoc_args[ 'wc_webhook_order_updated_id' ]
                );

                WP_CLI::success(
                    sprintf(
                        'WooCommerce Order Updated Webhook ID option set: %s',
                        $assoc_args[ 'wc_webhook_order_updated_id' ]
                    )
                );
            }
        }

        if( ! empty( $assoc_args[ 'wc_webhook_order_deleted_id' ] ) ) {

            if( $assoc_args[ 'wc_webhook_order_deleted_id' ] === 'delete' ) {

                $this->settings_repository->delete( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_DELETED_ID );

                WP_CLI::success(
                    'WooCommerce Order Deleted Webhook ID option removed'
                );
            }

            if( $assoc_args[ 'wc_webhook_order_deleted_id' ] !== 'delete' ) {

                $webhook = wc_get_webhook( $assoc_args[ 'wc_webhook_order_deleted_id' ] );

                if( $webhook instanceof WC_Webhook || $webhook->get_topic() !== 'order.deleted' ) {
                    WP_CLI::error(
                        sprintf(
                            'Provided WooCommerce Order Deleted Webhook ID: %s do not exists or it has invalid topic ',
                            $assoc_args[ 'wc_webhook_order_deleted_id' ]
                        )
                    );
                }

                $this->settings_repository->set(
                    Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_DELETED_ID,
                    $assoc_args[ 'wc_webhook_order_deleted_id' ]
                );

                WP_CLI::success(
                    sprintf(
                        'WooCommerce Order Deleted Webhook ID option set: %s',
                        $assoc_args[ 'wc_webhook_order_deleted_id' ]
                    )
                );
            }
        }
	}
}
