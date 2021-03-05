<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Shipping_Rest_Api_Validator extends Stamp_IC_WC_Abstract_Rest_Api_Validator {

	public function validate_get_shipping_data( WP_REST_Request $request ) {

		$params = array(
			'products' => $request->get_param( 'products' ),
			'shipping' => $request->get_param( 'shipping' ),
		);

		if( empty( $params[ 'products' ] ) || ! is_array( $params[ 'products' ] ) ) {
			return new WP_Error(
				'missing_parameter',
				__( 'Missing products parameter', STAMP_IC_WC_TEXT_DOMAIN ),
				array( 'status' => 422 )
			);
		}

		if( empty( $params[ 'shipping' ] ) || ! is_array( $params[ 'shipping' ] ) ) {
			return new WP_Error(
				'missing_parameter',
				__( 'Missing shipping parameter', STAMP_IC_WC_TEXT_DOMAIN ),
				array( 'status' => 422 )
			);
		}

		if( empty( $params[ 'shipping' ]['country'] ) ) {
			return new WP_Error(
				'missing_parameter',
				__( 'Missing shipping country parameter', STAMP_IC_WC_TEXT_DOMAIN ),
				array( 'status' => 422 )
			);
		}

		if( empty( $params[ 'shipping' ]['city'] ) ) {
			return new WP_Error(
				'missing_parameter',
				__( 'Missing shipping city parameter', STAMP_IC_WC_TEXT_DOMAIN ),
				array( 'status' => 422 )
			);
		}

		if( empty( $params[ 'shipping' ]['postcode'] ) ) {
			return new WP_Error(
				'missing_parameter',
				__( 'Missing shipping postcode parameter', STAMP_IC_WC_TEXT_DOMAIN ),
				array( 'status' => 422 )
			);
		}

		if( empty( $params[ 'shipping' ]['address'] ) ) {
			return new WP_Error(
				'missing_parameter',
				__( 'Missing shipping address parameter', STAMP_IC_WC_TEXT_DOMAIN ),
				array( 'status' => 422 )
			);
		}

		foreach ( $params[ 'products' ] as $product_params ) {

			if( empty( $product_params[ 'id' ] ) ) {
				return new WP_Error(
					'missing_parameter',
					__( 'Missing product id parameter', STAMP_IC_WC_TEXT_DOMAIN ),
					array( 'status' => 422 )
				);
			}

			if( empty( $product_params[ 'qty' ] ) ) {
				return new WP_Error(
					'missing_parameter',
					__( 'Missing product qty parameter', STAMP_IC_WC_TEXT_DOMAIN ),
					array( 'status' => 422 )
				);
			}

			$product = wc_get_product( sanitize_text_field( $product_params[ 'id' ] ) );

			if( ! $product instanceof WC_Product ) {
				return new WP_Error(
					'product_not_found',
					__( 'Product not found', STAMP_IC_WC_TEXT_DOMAIN ),
					array( 'status' => 404, 'product_id' => $product_params[ 'id' ] )
				);
			}
		}

		return true;
	}
}
