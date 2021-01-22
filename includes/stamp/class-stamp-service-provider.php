<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Automattic\WooCommerce\Vendor\League\Container\ServiceProvider\AbstractServiceProvider;

class Stamp_IC_WC_Stamp_Service_Provider extends AbstractServiceProvider {

	protected $provides = array(
		'Stamp_IC_WC_Api_Client',
		'Stamp_IC_WC_Stamp_Loader',
		'Stamp_IC_WC_Api_Cli_Command',
	);

	public function register() {

		$container = $this->getContainer();

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$container->add('Stamp_IC_WC_Api_Cli_Command' )
			          ->addMethodCall( 'set_api_client', array( 'Stamp_IC_WC_Api_Client' ) );
		}
	}
}
