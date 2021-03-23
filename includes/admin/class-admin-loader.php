<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Admin_Loader extends Stamp_IC_WooCommerce_Abstract_Loader {

	/* @var Stamp_IC_WC_Admin_Settings $admin_settings */
	protected $admin_settings;

	public function init() {

		$admin_settings = new Stamp_IC_WC_Admin_Settings();

		$settings_repository = new Stamp_IC_WC_Settings_Repository();
		$admin_settings->set_settings_repository( $settings_repository );

		$api_client = new Stamp_IC_WC_Api_Client();
		$api_client->set_api_url( STAMP_API_URL );
		$api_client->set_api_token( $settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY ) );
		$admin_settings->set_api_client( $api_client );

		$admin_settings->set_notifications_repository( new Stamp_IC_WC_Settings_Notifications_Repository() );

		$this->set_admin_settings( $admin_settings );
	}

	/**
	 * @return Stamp_IC_WC_Admin_Settings
	 */
	public function get_admin_settings() {
		return $this->admin_settings;
	}

	/**
	 * @param Stamp_IC_WC_Admin_Settings $admin_settings
	 *
	 * @return Stamp_IC_WC_Admin_Loader
	 */
	public function set_admin_settings( Stamp_IC_WC_Admin_Settings $admin_settings ) {
		$this->admin_settings = $admin_settings;
		return $this;
	}

	public function run() {
		if( is_admin() && current_user_can( 'manage_woocommerce' ) ) {
			add_action( 'admin_menu', array( $this->get_admin_settings(), 'register_admin_menu_items' ) );
			add_action( 'init', array( $this->get_admin_settings(), 'save_settings' ), 15 );
			add_action( 'init', array( $this->get_admin_settings(), 'test_stamp_api_credentials' ), 20 );
			add_filter( 'plugin_action_links_instant-checkout-woocommerce/instant-checkout-woocommerce.php', array( $this->get_admin_settings(), 'add_settings_link' ) );
		}
	}
}
