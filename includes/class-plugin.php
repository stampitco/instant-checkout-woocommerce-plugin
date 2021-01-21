<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WooCommerce_Plugin {

	protected $loaders = array();

	public function __construct( array $loaders ) {
		$this->set_loaders( $loaders );
	}

	/**
	 * @return array
	 */
	public function get_loaders(): array {
		return $this->loaders;
	}

	/**
	 * @param array $loaders
	 */
	public function set_loaders( array $loaders ): void {

		/* @var Stamp_IC_WooCommerce_Abstract_Loader $loader */
		foreach ( $loaders as $loader ) {
			if( ! $loader instanceof Stamp_IC_WooCommerce_Abstract_Loader ) {
				throw new RuntimeException(
					sprintf(
						'Class %s must be an instance of %s',
						get_class( $loader ),
						'Stamp_IC_WooCommerce_Abstract_Loader'
					)
				);
			}
		}

		$this->loaders = $loaders;
	}

	public function init_loaders() {
		/* @var Stamp_IC_WooCommerce_Abstract_Loader $loader */
		foreach ( $this->get_loaders() as $loader ) {
			$loader->run();
		}
	}

	public function run() {
		$this->init_loaders();
	}
}
