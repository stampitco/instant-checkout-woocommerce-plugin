<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Checkout_Style extends Stamp_IC_WC_Abstract_Style {

	const NAME = 'stampIcCheckoutStyle';

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
	 * @return Stamp_IC_WC_Checkout_Style
	 */
	public function set_settings_repository( Stamp_IC_WC_Settings_Repository $settings_repository ) {
		$this->settings_repository = $settings_repository;
		return $this;
	}

	public function name() {
		return static::NAME;
	}

	public function url() {
		return STAMP_IC_WC_PLUGIN_URL . '/assets/dist/public/css/checkout.css';
	}

	public function should_enqueue() {
		return true;
	}

	public function after_enqueue() {

		$additional_css = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::ADDITIONAL_CSS );

		if( ! empty( $additional_css ) ) {
			wp_add_inline_style( $this->name(), $additional_css );
		}
	}
}
