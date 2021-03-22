<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Shipping_Rest_Api_Controller extends Stamp_IC_WC_Abstract_Rest_Api_Controller {

	protected $rest_base = 'v1/shipping';

	/* @var Stamp_IC_WC_Shipping_Service $shipping_service */
	protected $shipping_service;

	/* @var Stamp_IC_WC_Shipping_Rest_Api_Validator $shipping_validator */
	protected $shipping_validator;

	public function register_routes() {
		register_rest_route(
			$this->namespace,
			$this->rest_base,
			array(
				array(
					'methods' => WP_REST_Server::CREATABLE,
					'callback' => array( $this, 'get_shipping_data' ),
					'permission_callback' => array( $this, 'authorize' ),
				),
			)
		);
	}

	/**
	 * @return Stamp_IC_WC_Shipping_Service
	 */
	public function get_shipping_service() {
		return $this->shipping_service;
	}

	/**
	 * @param Stamp_IC_WC_Shipping_Service $shipping_service
	 *
	 * @return Stamp_IC_WC_Shipping_Rest_Api_Controller
	 */
	public function set_shipping_service( Stamp_IC_WC_Shipping_Service $shipping_service ) {
		$this->shipping_service = $shipping_service;
		return $this;
	}

	/**
	 * @return Stamp_IC_WC_Shipping_Rest_Api_Validator
	 */
	public function get_shipping_validator() {
		return $this->shipping_validator;
	}

	/**
	 * @param Stamp_IC_WC_Shipping_Rest_Api_Validator $shipping_validator
	 *
	 * @return Stamp_IC_WC_Shipping_Rest_Api_Controller
	 */
	public function set_shipping_validator( Stamp_IC_WC_Shipping_Rest_Api_Validator $shipping_validator ) {
		$this->shipping_validator = $shipping_validator;
		return $this;
	}

	public function get_shipping_data( WP_REST_Request $request ) {

		$result = $this->shipping_validator->validate( $request, 'get_shipping_data' );

		if( is_wp_error( $result ) ) {
			return $result;
		}

		return $this->shipping_service->get_shipping_rates(
			$request->get_param( 'products' ),
			$request->get_param( 'shipping' )
		);
	}
}
