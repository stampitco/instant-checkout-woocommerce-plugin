<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Automattic\WooCommerce\Vendor\League\Container\ServiceProvider\AbstractServiceProvider;

class Stamp_IC_WC_Rest_Api_Service_Provider extends AbstractServiceProvider {

	protected $provides = array(
		'Stamp_IC_WC_Rest_Api_Loader',
		'Stamp_IC_WC_Shipping_Rest_Api_Controller',
		'Stamp_IC_WC_Shipping_Rest_Api_Validator',
	);

	public function register() {

		$container = $this->getContainer();

		$container->add( 'Stamp_IC_WC_Shipping_Rest_Api_Validator' );

		$container->add( 'Stamp_IC_WC_Shipping_Rest_Api_Controller' )
			->addMethodCall( 'set_shipping_service', array( 'Stamp_IC_WC_Shipping_Service' ) )
			->addMethodCall( 'set_shipping_validator', array( 'Stamp_IC_WC_Shipping_Rest_Api_Validator' ) );

		$container->add( 'Stamp_IC_WC_Rest_Api_Loader' )
			->addMethodCall(
				'set_controllers',
				array(
					array(
						$container->get( 'Stamp_IC_WC_Shipping_Rest_Api_Controller' ),
					)
				)
			);
	}
}
