<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) || ! defined( 'WP_CLI' ) ) {
	die;
}
abstract class Stamp_IC_WooCommerce_Abstract_Cli_Command {

	public function namespace() {
		return 'stamp-ic-wc';
	}

	public function definition() {
		return array(
			'shortdesc' => $this->short_description(),
			'synopsis' => $this->synopsis(),
			'when' => 'after_wp_load',
			'longdesc' => $this->long_description(),
		);
	}

	public function long_description() {
		return sprintf(
			'## EXAMPLES \n\n wp %s %s',
			$this->namespace(),
			$this->name()
		);
	}

	abstract public function name();

	abstract public function short_description();

	abstract public function synopsis();

	abstract public function run( $args, $assoc_args );
}
