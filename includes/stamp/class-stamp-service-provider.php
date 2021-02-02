<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Automattic\WooCommerce\Vendor\League\Container\ServiceProvider\AbstractServiceProvider;
use GuzzleHttp\Client;

class Stamp_IC_WC_Stamp_Service_Provider extends AbstractServiceProvider {

	protected $provides = array(
		'Stamp_IC_WC_Api_Client',
		'Stamp_IC_WC_Stamp_Loader',
		'Stamp_IC_WC_Api_Cli_Command',
		'Stamp_IC_WC_Credentials',
		'Stamp_IC_WC_Webhooks',
	);

	public function register() {

		$container = $this->getContainer();

		$settings_repository = $container->get( 'Stamp_IC_WC_Settings_Repository' );

		$http_client = new Client( array(
			'base_uri' => STAMP_API_URL,
			'verify' => false,
			'headers' => array(
				'X-IC-AppKey' => $settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY ),
			),
		) );

		$container->add('Stamp_IC_WC_Api_Client' )->addArgument( $http_client );
		
		$container->add('Stamp_IC_WC_Credentials' )
		          ->addMethodCall( 'set_api_client', array( 'Stamp_IC_WC_Api_Client' ) )
		          ->addMethodCall( 'set_settings_repository', array( $settings_repository ) );

        $container->add('Stamp_IC_WC_Webhooks' )
            ->addMethodCall( 'set_settings_repository', array( $settings_repository ) );

		$container->add('Stamp_IC_WC_Stamp_Loader' )
					->addMethodCall( 'set_container', array( $container ) )
		            ->addMethodCall( 'set_api_client', array( 'Stamp_IC_WC_Api_Client' ) )
		            ->addMethodCall( 'set_wc_credentials', array( 'Stamp_IC_WC_Credentials' ) )
		            ->addMethodCall( 'set_wc_webhooks', array( 'Stamp_IC_WC_Webhooks' ) );

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$container->add('Stamp_IC_WC_Api_Cli_Command' )
			          ->addMethodCall( 'set_api_client', array( 'Stamp_IC_WC_Api_Client' ) );
		}
	}
}
