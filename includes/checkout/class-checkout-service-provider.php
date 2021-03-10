<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

use Automattic\WooCommerce\Vendor\League\Container\ServiceProvider\AbstractServiceProvider;

class Stamp_IC_WC_Checkout_Service_Provider extends AbstractServiceProvider {

    protected $provides = array(
        'Stamp_IC_WC_Checkout_Loader',
        'Stamp_IC_WC_Checkout_Button',
        'Stamp_IC_WC_Checkout_Ajax',
    );

    public function register() {

        $container = $this->getContainer();

	    $container->add( 'Stamp_IC_WC_Checkout_Ajax' )
	              ->addMethodCall( 'set_settings_repository', array( 'Stamp_IC_WC_Settings_Repository' ) );

        $container->add( 'Stamp_IC_WC_Checkout_Button' );

        $container->add( 'Stamp_IC_WC_Checkout_Loader' )
            ->addMethodCall( 'set_container', array( $container ) )
            ->addMethodCall( 'set_wc_checkout_button', array( 'Stamp_IC_WC_Checkout_Button' ) )
            ->addMethodCall( 'set_wc_checkout_ajax', array( 'Stamp_IC_WC_Checkout_Ajax' ) );
    }
}
