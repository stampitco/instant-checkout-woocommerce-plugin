<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

abstract class Stamp_IC_WC_Abstract_Rest_Api_Validator {
	public function validate( WP_REST_Request $request, $what ) {

		$method = 'validate_' . $what;

		if( ! method_exists( $this, $method ) ) {
			throw new RuntimeException(
				sprintf(
					'Validation method: %s not found in class: %s',
					$method,
					get_class( $this )
				)
			);
		}

		return $this->$method( $request );
	}
}
