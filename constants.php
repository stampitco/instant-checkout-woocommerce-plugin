<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'STAMP_IC_WC_PLUGIN_URL' ) ) {
	define(
		'STAMP_IC_WC_PLUGIN_URL',
		untrailingslashit(
			plugins_url(
				basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ )
			)
		)
	);
}

if ( ! defined( 'STAMP_IC_WC_PLUGIN_DIR' ) ) {
	define( 'STAMP_IC_WC_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'STAMP_IC_WC_TEXT_DOMAIN' ) ) {
	define( 'STAMP_IC_WC_TEXT_DOMAIN', 'stamp-ic-wc' );
}

if ( ! defined( 'STAMP_IC_WC_VERSION' ) ) {
	define( 'STAMP_IC_WC_VERSION', '1.0.0' );
}

if ( ! defined( 'STAMP_API_URL' ) ) {
	define( 'STAMP_API_URL', 'https://stamp-api-instantcheckout-int.azurewebsites.net' );
}

if ( ! defined( 'STAMP_WEB_URL' ) ) {
	define( 'STAMP_WEB_URL', 'https://stamp-web-instantcheckout-int.azurewebsites.net' );
}
