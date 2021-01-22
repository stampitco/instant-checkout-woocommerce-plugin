<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Admin_Loader extends Stamp_IC_WooCommerce_Abstract_Loader {

	/* @var Stamp_IC_WC_Admin_Settings $admin_settings */
	protected $admin_settings;

	/**
	 * @return Stamp_IC_WC_Admin_Settings
	 */
	public function get_admin_settings(): Stamp_IC_WC_Admin_Settings {
		return $this->admin_settings;
	}

	/**
	 * @param Stamp_IC_WC_Admin_Settings $admin_settings
	 *
	 * @return Stamp_IC_WC_Admin_Loader
	 */
	public function set_admin_settings( Stamp_IC_WC_Admin_Settings $admin_settings ): Stamp_IC_WC_Admin_Loader {
		$this->admin_settings = $admin_settings;
		return $this;
	}

	public function run() {
		if( is_admin() ) {
			add_action( 'admin_menu', array( $this->admin_settings, 'register_admin_menu_items' ) );
			add_action( 'init', array( $this->admin_settings, 'save_settings' ) );
		}
	}
}
