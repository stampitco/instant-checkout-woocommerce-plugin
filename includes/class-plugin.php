<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WooCommerce_Plugin {

	/* @var Stamp_IC_WC_DI_Container $container */
	protected $container;

	protected $loaders = array();

	/**
	 * @return array
	 */
	public function get_loaders(){
		return $this->loaders;
	}

	public function set_loaders( array $loaders ) {

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

		return $this;
	}

	/**
	 * @return Stamp_IC_WC_DI_Container
	 */
	public function get_container() {
		return $this->container;
	}

	/**
	 * @param Stamp_IC_WC_DI_Container $container
	 *
	 * @return Stamp_IC_WooCommerce_Plugin
	 */
	public function set_container( Stamp_IC_WC_DI_Container $container ) {
		$this->container = $container;
		return $this;
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
