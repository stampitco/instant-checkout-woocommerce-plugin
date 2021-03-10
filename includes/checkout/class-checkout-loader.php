<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Stamp_IC_WC_Checkout_Loader extends Stamp_IC_WooCommerce_Abstract_Loader {

    /* @var Stamp_IC_WC_Checkout_Button $wc_checkout_button */
    protected $wc_checkout_button;

	/* @var Stamp_IC_WC_Checkout_Ajax $wc_checkout_ajax */
	protected $wc_checkout_ajax;

	/**
	 * @return Stamp_IC_WC_Checkout_Button
	 */
	public function get_wc_checkout_button(): Stamp_IC_WC_Checkout_Button {
		return $this->wc_checkout_button;
	}

	/**
	 * @param Stamp_IC_WC_Checkout_Button $wc_checkout_button
	 *
	 * @return Stamp_IC_WC_Checkout_Loader
	 */
	public function set_wc_checkout_button( Stamp_IC_WC_Checkout_Button $wc_checkout_button ): Stamp_IC_WC_Checkout_Loader {
		$this->wc_checkout_button = $wc_checkout_button;
		return $this;
	}

	/**
	 * @return Stamp_IC_WC_Checkout_Ajax
	 */
	public function get_wc_checkout_ajax(): Stamp_IC_WC_Checkout_Ajax {
		return $this->wc_checkout_ajax;
	}

	/**
	 * @param Stamp_IC_WC_Checkout_Ajax $wc_checkout_ajax
	 *
	 * @return Stamp_IC_WC_Checkout_Loader
	 */
	public function set_wc_checkout_ajax( Stamp_IC_WC_Checkout_Ajax $wc_checkout_ajax ): Stamp_IC_WC_Checkout_Loader {
		$this->wc_checkout_ajax = $wc_checkout_ajax;
		return $this;
	}

    public function run() {
		$this->init_button();
		$this->init_ajax();
    }

    protected function init_button() {
	    add_action( 'after_setup_theme', array( $this->wc_checkout_button, 'init_checkout_button_display' ) );
    }

    protected function init_ajax() {

		$actions = array(
			'wp_ajax_stamp_ic_checkout_get_checkout_url',
			'wp_ajax_nopriv_stamp_ic_checkout_get_checkout_url',
			'wp_ajax_stamp_ic_checkout_clear_cart',
			'wp_ajax_nopriv_stamp_ic_checkout_clear_cart',
		);

		foreach ( $actions as $action ) {
			add_action( $action, array( $this->wc_checkout_ajax, 'ajax_handler' ) );
		}
    }
}