<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

abstract class Stamp_IC_WooCommerce_Abstract_Loader {

	/* @var Stamp_IC_WC_DI_Container $container */
	protected $container;

	protected $commands = array();

	abstract public function run();

	/**
	 * @return array
	 */
	public function get_commands(): array {
		return $this->commands;
	}

	/**
	 * @param array $commands
	 */
	public function set_commands( array $commands ): void {

		/* @var Stamp_IC_WooCommerce_Abstract_Cli_Command $command */
		foreach ( $commands as $command ) {
			if( ! $command instanceof Stamp_IC_WooCommerce_Abstract_Cli_Command ) {
				throw new RuntimeException(
					sprintf(
						'Class %s must be an instance of %s',
						get_class( $command ),
						'Stamp_IC_WooCommerce_Abstract_Cli_Command'
					)
				);
			}
		}

		$this->commands = $commands;
	}

	/**
	 * @return Stamp_IC_WC_DI_Container
	 */
	public function get_container(): Stamp_IC_WC_DI_Container {
		return $this->container;
	}

	/**
	 * @param Stamp_IC_WC_DI_Container $container
	 *
	 * @return Stamp_IC_WooCommerce_Abstract_Loader
	 */
	public function set_container( Stamp_IC_WC_DI_Container $container ): Stamp_IC_WooCommerce_Abstract_Loader {
		$this->container = $container;
		return $this;
	}

	public function register_cli_commands() {}
}