<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Stamp_IC_WC_Checkout_Loader extends Stamp_IC_WooCommerce_Abstract_Loader {

    /* @var Stamp_IC_WC_Checkout $wc_checkout */
    protected $wc_checkout;

    /**
     * @return Stamp_IC_WC_Checkout
     */
    public function get_wc_checkout(): Stamp_IC_WC_Checkout {
        return $this->wc_checkout;
    }

    public function set_wc_checkout( Stamp_IC_WC_Checkout $wc_checkout ): Stamp_IC_WC_Checkout_Loader {
        $this->wc_checkout = $wc_checkout;
        return $this;
    }

    public function run() {
        add_action( 'stamp_ic_checkout_do_checkout_button', array( $this->wc_checkout, 'show_checkout_button' ) );
        add_action( 'wp_ajax_stamp_ic_checkout_get_checkout_url', array( $this->wc_checkout, 'get_checkout_url_ajax_handler' ) );
    }
}