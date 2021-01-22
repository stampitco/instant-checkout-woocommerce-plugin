<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Automattic\WooCommerce\Vendor\League\Container\Container;

class Stamp_IC_WC_DI_Container extends Container {

	static $instance;

	public static function instance() {

		if( ! static::$instance instanceof Stamp_IC_WC_DI_Container ) {
			static::$instance = new Stamp_IC_WC_DI_Container();
		}

		return static::$instance;
	}
}
