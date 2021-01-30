<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Stamp_IC_WC_Webhooks {

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
     * @return Stamp_IC_WC_Credentials
     */
    public function set_settings_repository( Stamp_IC_WC_Settings_Repository $settings_repository ): Stamp_IC_WC_Webhooks {
        $this->settings_repository = $settings_repository;
        return $this;
    }

}
