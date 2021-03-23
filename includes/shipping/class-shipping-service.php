<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Shipping_Service {

	public function get_shipping_rates( array $products, array $destination ) {

		$results = array();

		if ( ! wc_shipping_enabled() || 0 === wc_get_shipping_method_count( true ) ) {
			return $results;
		}

		if ( defined( 'WC_ABSPATH' ) ) {
			// WC 3.6+ - Cart and other frontend functions are not included for REST requests.
			include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
			include_once WC_ABSPATH . 'includes/wc-notice-functions.php';
			include_once WC_ABSPATH . 'includes/wc-template-hooks.php';
		}

		if ( null === WC()->session ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session = new $session_class();
			WC()->session->init();
		}

		if ( null === WC()->customer ) {
			WC()->customer = new WC_Customer( 0, false );
			WC()->customer->set_shipping_country( $destination[ 'country' ] );
			WC()->customer->set_shipping_state( ! empty( $destination[ 'state' ] ) ? $destination[ 'state' ] : '' );
			WC()->customer->set_shipping_postcode( $destination[ 'postcode' ] );
			WC()->customer->set_shipping_city( $destination[ 'city' ] );
			WC()->customer->set_shipping_address( $destination[ 'address' ] );
		}

		if ( null === WC()->cart ) {
			WC()->cart = new WC_Cart();
			// We need to force a refresh of the cart contents from session here (cart contents are normally refreshed on wp_loaded, which has already happened by this point).
			WC()->cart->get_cart();
		}

		foreach ( $products as $product_data ) {

			$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $product_data['id'] ) );
			$product           = wc_get_product( $product_id );
			$quantity          = wc_stock_amount( wp_unslash( $product_data['qty'] ) );
			$variation_id      = 0;
			$variation         = array();

			if ( $product && 'variation' === $product->get_type() ) {
				$variation_id = $product_id;
				$product_id   = $product->get_parent_id();
				$variation    = $product->get_variation_attributes();
			}

			WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
		}

		$packages = WC()->shipping()->calculate_shipping( WC()->cart->get_shipping_packages() );

		if( ! empty( $packages ) ) {
			foreach ( $packages as $package ) {
				/* @var WC_Shipping_Rate $rate */
				foreach ( $package['rates'] as $rate ) {
					$results[] = array(
						'method_id' => $rate->get_method_id(),
						'instance_id' => $rate->get_instance_id(),
						'name' => $rate->get_label(),
						'cost' => (float) $rate->get_cost(),
					);
				}
			}
		}

		WC()->cart->empty_cart();

		return $results;
	}
}
