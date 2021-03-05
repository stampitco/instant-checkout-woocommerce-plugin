<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Automattic\WooCommerce\Vendor\League\Container\ServiceProvider\AbstractServiceProvider;

class Stamp_IC_WC_Shipping_Service_Provider extends AbstractServiceProvider {

	protected $provides = array(
		'Stamp_IC_WC_Shipping_Service',
	);

	public function register() {
		$container = $this->getContainer();
		$container->add( 'Stamp_IC_WC_Shipping_Service' );
	}
}
