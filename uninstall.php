<?php
/**
 * Fired when the plugin is uninstalled.
 */
if ( ! defined( 'WPINC' ) || ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

if( WP_UNINSTALL_PLUGIN !== 'instant-checkout-woocommerce-plugin/instant-checkout-woocommerce.php' ) {
	return;
}

require_once __DIR__ . '/vendor/autoload.php';

$container = Stamp_IC_WC_DI_Container::instance();

$container->addServiceProvider( new Stamp_IC_WC_Settings_Service_Provider() );

/* @var Stamp_IC_WC_Settings_Repository $settings_repository */
$settings_repository = $container->get( 'Stamp_IC_WC_Settings_Repository' );

$settings_repository->delete( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY );
$settings_repository->delete( Stamp_IC_WC_Settings_Repository::STAMP_API_URL );
$settings_repository->delete( Stamp_IC_WC_Settings_Repository::WC_CREDENTIALS_ID );