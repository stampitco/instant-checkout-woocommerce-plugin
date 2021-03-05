<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

abstract class Stamp_IC_WC_Abstract_Rest_Api_Controller extends WP_REST_Controller {

	protected $namespace = 'wc-ic-stamp';

	public function authorize( WP_REST_Request $request ): bool {
		return current_user_can( 'manage_woocommerce' );
	}
}
