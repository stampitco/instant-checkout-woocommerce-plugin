<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Automattic\WooCommerce\Vendor\League\Container\ServiceProvider\AbstractServiceProvider;

class Stamp_IC_WC_Settings_Service_Provider extends AbstractServiceProvider {

	protected $provides = array(
		'Stamp_IC_WC_Settings_Repository',
		'Stamp_IC_WC_Settings_Loader',
		'Stamp_IC_WC_Settings_Cli_Command',
	);

	public function register() {

		$container = $this->getContainer();

		$container->add('Stamp_IC_WC_Settings_Repository' );

		$container->add('Stamp_IC_WC_Settings_Loader' )
					->addMethodCall( 'set_container', array( $container ) )
					->addMethodCall( 'set_settings_repository', array( 'Stamp_IC_WC_Settings_Repository' ) );

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$container->add('Stamp_IC_WC_Settings_Cli_Command' )
			          ->addMethodCall( 'set_settings_repository', array( 'Stamp_IC_WC_Settings_Repository' ) );
		}
	}
}
