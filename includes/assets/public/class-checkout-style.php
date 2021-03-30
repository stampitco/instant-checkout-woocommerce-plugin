<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Checkout_Style extends Stamp_IC_WC_Abstract_Style {

	const NAME = 'stampIcCheckoutStyle';

	public function name() {
		return static::NAME;
	}

	public function url() {
		return STAMP_IC_WC_PLUGIN_URL . '/assets/dist/public/css/checkout.css';
	}

	public function should_enqueue() {
		return true;
	}
}
