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
		return ( function_exists( 'is_product' ) && is_product() ) || ( function_exists( 'is_cart' ) && is_cart() );
	}

	public function data( array $params = array() ): array {
		return array(
			'api' => array(
				'nonce' => wp_create_nonce( 'stamp-ic-checkout' ),
				'nonceName' => 'stamp-ic-checkout',
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'getCheckoutUrlAction' => 'stamp_ic_checkout_get_checkout_url',
				'clearCartAction' => 'stamp_ic_checkout_clear_cart',
			),
            'debug' => defined( 'WP_DEBUG' ) && WP_DEBUG === true ? 1 : 0,
			'overlay' => array(
				'linkText' => __( 'Click here', STAMP_IC_WC_TEXT_DOMAIN ),
				'overlayText' => __( 'No longer see the Instant Checkout window?', STAMP_IC_WC_TEXT_DOMAIN ),
				'logo' => STAMP_IC_WC_PLUGIN_URL . '/assets/dist/public/images/checkout/instant_checkout-logo.png',
			),
			'page' => array(
				'isProduct' => is_product(),
				'isCart' => is_cart(),
			),
			'siteUrl' => get_home_url(),
			'popUpTempUrl' => STAMP_IC_WC_PLUGIN_URL . '/assets/public/checkout/popup.html',
			'instantCheckoutButtonText' => apply_filters( 'stamp_ic_checkout_button_text', __( 'Instant Checkout', STAMP_IC_WC_TEXT_DOMAIN ) ),
			'orderDoneText' => __( 'Your order was placed', STAMP_IC_WC_TEXT_DOMAIN ),
        );
	}
}
