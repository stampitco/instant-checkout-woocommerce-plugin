<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Instant Checkout for WooCommerce
 * Plugin URI:        https://stampit.co
 * Description:       Instant Checkout for WooCommerce. Better checkout experience, without password and too many clicks.
 * Version:           1.0.0
 * Author:            Stamp
 * Author URI:        https://stampit.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       stamp-ic-wc
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/functions.php';

function activate_stamp_ic_wc() {
	$activator = new Stamp_IC_WC_Activator();
	$activator->activate( apply_filters( 'active_plugins', get_option('active_plugins' ) ) );
}

function deactivate_stamp_ic_wc() {
	$deactivator = new Stamp_IC_WC_Deactivator();
	$deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate_bg_email_octopus_connector' );
register_deactivation_hook( __FILE__, 'deactivate_bg_email_octopus_connector' );

function run_stamp_ic_wc() {

	$activator = new Stamp_IC_WC_Activator();

	$missing_plugins = $activator->check_for_required_plugins( apply_filters( 'active_plugins', get_option('active_plugins' ) ) );

	if( ! empty( $missing_plugins ) ) {
		array_map( array( $activator, 'report_missing_plugin' ), $missing_plugins );
		return;
	}

	$container = Stamp_IC_WC_DI_Container::instance();

	$container->addServiceProvider( new Stamp_IC_WC_Settings_Service_Provider() );
	$container->addServiceProvider( new Stamp_IC_WC_Admin_Service_Provider() );
	$container->addServiceProvider( new Stamp_IC_WC_Notifications_Service_Provider() );
	$container->addServiceProvider( new Stamp_IC_WC_Stamp_Service_Provider() );
	$container->addServiceProvider( new Stamp_IC_WC_Assets_Service_Provider() );
	$container->addServiceProvider( new Stamp_IC_WC_Checkout_Service_Provider() );

	$plugin = new Stamp_IC_WooCommerce_Plugin();

	$plugin->set_container( $container )
			->set_loaders(
				array(
					$container->get( 'Stamp_IC_WC_Settings_Loader' ),
					$container->get( 'Stamp_IC_WC_Admin_Loader' ),
					$container->get( 'Stamp_IC_WC_Stamp_Loader' ),
					$container->get( 'Stamp_IC_WC_Assets_Loader' ),
					$container->get( 'Stamp_IC_WC_Checkout_Loader' ),
				)
			);

	$plugin->run();
}

add_action( 'plugins_loaded', 'run_stamp_ic_wc' );