<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

abstract class Stamp_IC_WooCommerce_Abstract_Loader {

	protected $commands = array();

	/**
	 * Stamp_IC_WooCommerce_Abstract_Loader constructor.
	 */
	public function __construct() {
		$this->init();
		$this->register_cli_commands();
	}

	public function init() {}

	public function register_cli_commands() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			/* @var Stamp_IC_WooCommerce_Abstract_Cli_Command $command */
			foreach ( $this->get_commands() as $command ) {
				\WP_CLI::add_command(
					sprintf( '%s %s', $command->namespace(), $command->name() ),
					array( $command, 'run' ),
					$command->definition()
				);
			}
		}
	}

	abstract public function run();

	/**
	 * @return array
	 */
	public function get_commands() {
		return $this->commands;
	}

	/**
	 * @param array $commands
	 */
	public function set_commands( array $commands ) {

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
}