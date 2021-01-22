<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Automattic\WooCommerce\Vendor\League\Container\ServiceProvider\AbstractServiceProvider;

class Stamp_IC_WC_Admin_Service_Provider extends AbstractServiceProvider {

	protected $provides = array(
		'Stamp_IC_WC_Admin_Loader',
		'Stamp_IC_WC_Admin_Settings',
	);

	public function register() {

		$container = $this->getContainer();

		$container->add('Stamp_IC_WC_Admin_Settings' )
		          ->addMethodCall( 'set_settings_repository', array( 'Stamp_IC_WC_Settings_Repository' ) );

		$container->add('Stamp_IC_WC_Admin_Loader' )
		          ->addMethodCall( 'set_container', array( $container ) )
		          ->addMethodCall( 'set_admin_settings', array( 'Stamp_IC_WC_Admin_Settings' ) );
	}
}
