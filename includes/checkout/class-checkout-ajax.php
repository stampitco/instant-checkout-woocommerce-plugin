<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Checkout_Ajax {

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
	 * @return Stamp_IC_WC_Checkout_Ajax
	 */
	public function set_settings_repository( Stamp_IC_WC_Settings_Repository $settings_repository ) {
		$this->settings_repository = $settings_repository;
		return $this;
	}

	public function ajax_handler() {

		$params = ! empty( $_POST ) ? $_POST : array();

		$result = $this->authorize_request( $params, ! empty( $params[ 'action' ] ) ? $params[ 'action' ] : null );

		if( is_wp_error( $result ) ) {
			wp_send_json_error( $result, $result->get_error_code() );
		}

		$result = call_user_func( array( $this, $this->get_ajax_handlers()[ $params[ 'action' ] ] ), $params );

		if( is_wp_error( $result ) ) {
			wp_send_json_error( $result, $result->get_error_code() );
		}

		wp_send_json_success( $result );
	}

	public function authorize_request( array $params, $action ) {
		return $this->check_nonce(
			'stamp-ic-checkout',
			! empty( $params[ 'stamp-ic-checkout' ] ) ? $params[ 'stamp-ic-checkout' ] : null,
			$action
		);
	}

	public function check_nonce( $name, $value, $action ) {

		if ( ! wp_verify_nonce( $value, $name ) ) {
			return new WP_Error(
				403,
				__(
					sprintf( 'You are not authorized to perform the action: %s', $action ),
					STAMP_IC_WC_TEXT_DOMAIN
				)
			);
		}

		return true;
	}

	public function check_action( $action ) {

		if ( ! in_array( $action, array_keys( $this->get_ajax_handlers() ) ) ) {
			return new WP_Error(
				422,
				__(
					sprintf( 'Invalid action: %s', $action ),
					STAMP_IC_WC_TEXT_DOMAIN
				)
			);
		}

		return true;
	}

	public function get_checkout_url( array $input ) {

		$items = ! empty( $input[ 'items' ] ) ? $input[ 'items' ] : array();

		if( ! empty( $input[ 'fromCart' ] ) ) {

			$items = array();

			foreach ( WC()->cart->get_cart() as $cart_item ) {

				if( ! empty( $cart_item[ 'product_id' ] ) && ! empty( $cart_item[ 'quantity' ] ) ) {

					$item = array(
						'product_id' => $cart_item[ 'product_id' ],
						'qty' => $cart_item[ 'quantity' ],
					);

					if( ! empty( $cart_item[ 'variation_id' ] ) ) {
						$item[ 'variation_id' ] = $cart_item[ 'variation_id' ];
					}

					$items[] = $item;
				}
			}
		}

		if( empty( $items ) ) {
			return new WP_Error(
				422,
				__( 'Missing items param', STAMP_IC_WC_TEXT_DOMAIN )
			);
		}

		$app_key = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY );

		if( empty( $app_key ) ) {
			return new WP_Error(
				422,
				__( 'Stamp App key missing', STAMP_IC_WC_TEXT_DOMAIN )
			);
		}

		$web_url = STAMP_WEB_URL;

		if( empty( $web_url ) || ! filter_var( $web_url, FILTER_VALIDATE_URL ) ) {
			return new WP_Error(
				422,
				__( 'Stamp Web URL missing or invalid', STAMP_IC_WC_TEXT_DOMAIN )
			);
		}

		$query_args = array(
			'appKey' => $app_key,
			'products' => []
		);

		foreach ( $items as $item ) {

			$product_id = ! empty( $item[ 'product_id' ] ) ? $item[ 'product_id' ] : null;
			$variation_id = ! empty( $item[ 'variation_id' ] ) ? $item[ 'variation_id' ] : null;

			$product = wc_get_product( $product_id );

			if( ! $product instanceof WC_Product ) {
				return new WP_Error(
					404,
					__( 'Product not found', STAMP_IC_WC_TEXT_DOMAIN )
				);
			}

			$qty = ! empty( $item[ 'qty' ] ) ? $item[ 'qty' ] : null;

			if( empty( $qty ) ) {
				return new WP_Error(
					422,
					__( 'Product qty is empty', STAMP_IC_WC_TEXT_DOMAIN )
				);
			}

			$product_query_param = array(
				'i' => $product_id,
				'q' => $qty,
			);

			if( $product->is_type( 'variable' ) ) {

				if( is_null( $variation_id ) || ! in_array( $variation_id, $product->get_children() ) ) {
					return new WP_Error(
						422,
						__( 'Variation does not belong to this product', STAMP_IC_WC_TEXT_DOMAIN )
					);
				}

				$variation = wc_get_product( $variation_id );

				if( $variation instanceof WC_Product_Variation ) {
					$product_query_param[ 'i' ] = $variation_id;
				}
			}

			$query_args[ 'products' ][] = $product_query_param;
		}

		if( ! empty( $query_args[ 'products' ] ) ) {
			$query_args[ 'products' ] = json_encode( $query_args[ 'products' ] );
		}

		return array(
			'checkout_url' => sprintf(
				'%s/checkout?%s',
				untrailingslashit( $web_url ),
				http_build_query( $query_args )
			)
		);
	}

	public function clear_cart( array $input ) {
		WC()->cart->empty_cart();
		return true;
	}

	protected function get_ajax_handlers() {
		return array(
			'stamp_ic_checkout_get_checkout_url' => 'get_checkout_url',
			'stamp_ic_checkout_clear_cart' => 'clear_cart',
		);
	}
}
