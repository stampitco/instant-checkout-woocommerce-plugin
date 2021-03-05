<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Rest_Api_Loader extends Stamp_IC_WooCommerce_Abstract_Loader {

	protected $controllers = array();

	public function run() {
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ), 10 );
	}

	public function rest_api_init() {
		/* @var Stamp_IC_WC_Abstract_Rest_Api_Controller $controller */
		foreach ( $this->get_controllers() as $controller ) {
			$controller->register_routes();
		}
	}

	public function get_controllers(): array {
		return $this->controllers;
	}

	/**
	 * @param array $controllers
	 *
	 * @return Stamp_IC_WC_Rest_Api_Loader
	 */
	public function set_controllers( array $controllers ): Stamp_IC_WC_Rest_Api_Loader {

		/* @var Stamp_IC_WC_Abstract_Rest_Api_Controller $controller */
		foreach ( $controllers as $controller ) {
			if( ! $controller instanceof Stamp_IC_WC_Abstract_Rest_Api_Controller ) {
				throw new RuntimeException(
					sprintf(
						'Class %s must be an instance of %s',
						get_class( $controller ),
						'Stamp_IC_WC_Abstract_Rest_Api_Controller'
					)
				);
			}
		}

		$this->controllers = $controllers;

		return $this;
	}
}
