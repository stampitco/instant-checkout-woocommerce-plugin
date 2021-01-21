<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) || ! defined( 'WP_CLI' ) ) {
	die;
}
abstract class Stamp_IC_WooCommerce_Abstract_Cli_Command {

	public function namespace(): string {
		return 'stamp-ic-wc';
	}

	public function definition(): array {
		return array(
			'shortdesc' => $this->short_description(),
			'synopsis' => $this->synopsis(),
			'when' => 'after_wp_load',
			'longdesc' => $this->long_description(),
		);
	}

	public function long_description(): string {
		return sprintf(
			'## EXAMPLES \n\n wp %s %s',
			$this->namespace(),
			$this->name()
		);
	}

	abstract public function name(): string;

	abstract public function short_description(): string;

	abstract public function synopsis(): array;

	abstract public function run( $args, $assoc_args );
}
