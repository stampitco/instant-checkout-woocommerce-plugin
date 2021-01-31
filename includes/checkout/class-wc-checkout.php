<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Stamp_IC_WC_Checkout {

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
     * @return Stamp_IC_WC_Checkout
     */
    public function set_settings_repository( Stamp_IC_WC_Settings_Repository $settings_repository ): Stamp_IC_WC_Checkout {
        $this->settings_repository = $settings_repository;
        return $this;
    }

    public function get_checkout_url() {

    }

    public function show_checkout_button() {

    }
}
