<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Checkout_Script extends Stamp_IC_WC_Abstract_Script {

	const NAME = 'stampIcCheckout';

	public function name(): string {
		return static::NAME;
	}

	public function url(): string {
		return STAMP_IC_WC_PLUGIN_URL . '/assets/dist/public/js/checkout.js';
	}

	public function deps(): array {
		return array(
			'jquery',
		);
	}

	public function should_enqueue(): bool {
		return function_exists( 'is_product' ) && is_product();
	}

	public function data( array $params = array() ): array {
		return array(
			'nonce' => wp_create_nonce( 'stamp-ic-checkout' ),
		);
	}
}
