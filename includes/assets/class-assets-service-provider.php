<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Automattic\WooCommerce\Vendor\League\Container\ServiceProvider\AbstractServiceProvider;

class Stamp_IC_WC_Assets_Service_Provider extends AbstractServiceProvider {

	protected $provides = array(
		'Stamp_IC_WC_Assets_Loader',
	);

	public function register() {

		$container = $this->getContainer();

		$container->add('Stamp_IC_WC_Assets_Loader' )
			->addMethodCall( 'set_container', array( $container ) )
			->addMethodCall(
				'set_admin_scripts',
				array(
					array(
						new Stamp_IC_WC_Admin_Settings_Script(),
					)
				)
			)
			->addMethodCall(
				'set_admin_styles',
				array(
					array(
						new Stamp_IC_WC_Admin_Settings_Style(),
					)
				)
			)
			->addMethodCall(
				'set_public_scripts',
				array(
					array(
						new Stamp_IC_WC_Checkout_Script(),
					)
				)
			)
			->addMethodCall(
				'set_public_styles',
				array(
					array(
						new Stamp_IC_WC_Checkout_Style(),
					)
				)
			);
	}
}
