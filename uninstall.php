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

if( class_exists( 'WC_Webhook_Data_Store' ) ) {

	$webhook_data_store = new WC_Webhook_Data_Store();

	$webhook_order_updated = wc_get_webhook(
		$this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_UPDATED_ID )
	);

	if( $webhook_order_updated instanceof WC_Webhook ) {
		$webhook_data_store->delete( $webhook_order_updated );
	}

	$webhook_order_deleted = wc_get_webhook(
		$this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_DELETED_ID )
	);

	if( $webhook_order_deleted instanceof WC_Webhook ) {
		$webhook_data_store->delete( $webhook_order_deleted );
	}
}

global $wpdb;

$wpdb->delete(
$wpdb->prefix . 'woocommerce_api_keys',
	array(
		'key_id' => $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_CREDENTIALS_ID )
	),
	array(
		'%d'
	)
);

$settings_repository->delete( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY );
$settings_repository->delete( Stamp_IC_WC_Settings_Repository::WC_CREDENTIALS_ID );
$settings_repository->delete( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_UPDATED_ID );
$settings_repository->delete( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_DELETED_ID );