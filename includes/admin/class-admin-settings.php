<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Admin_Settings {

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
	 * @return Stamp_IC_WC_Admin_Settings
	 */
	public function set_settings_repository( Stamp_IC_WC_Settings_Repository $settings_repository ): Stamp_IC_WC_Admin_Settings {
		$this->settings_repository = $settings_repository;
		return $this;
	}

	public function register_admin_menu_items() {
		add_submenu_page(
			'options-general.php',
			__( 'Stamp Instant Checkout Settings', STAMP_IC_WC_TEXT_DOMAIN ),
			__( 'Instant Checkout', STAMP_IC_WC_TEXT_DOMAIN ),
			'manage_options',
			'stamp-ic-wc',
			array( $this, 'render' )
		);
	}

	public function save_settings() {

		if( empty( $_POST[ 'stamp_ic_wc_settings_nonce' ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['stamp_ic_wc_settings_nonce'], 'stamp_ic_wc_settings_nonce' ) ) {
			wp_die( __( 'Cheatin&#8217; huh?', STAMP_IC_WC_TEXT_DOMAIN ) );
		}

		if( ! empty( $_POST[ 'stamp_api_key' ] ) ) {
			$this->settings_repository->set(
				Stamp_IC_WC_Settings_Repository::STAMP_API_KEY,
				wc_clean( $_POST[ 'stamp_api_key' ] )
			);
		}

		wp_redirect(
			add_query_arg(
				array(
					'page' => 'stamp-ic-wc',
				),
				admin_url( 'options-general.php' )
			)
		);
	}

	public function render() {
		$stamp_api_key = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY );
		include __DIR__ . '/views/html-settings.php';
	}
}
