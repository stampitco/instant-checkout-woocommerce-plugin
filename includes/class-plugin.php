<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WooCommerce_Plugin {

	/* @var Stamp_IC_WC_DI_Container $container */
	protected $container;

	protected $loaders = array();

	protected $can_run_checked = false;

	protected $can_run = false;

	/**
	 * @return array
	 */
	public function get_loaders(): array {
		return $this->loaders;
	}

	public function set_loaders( array $loaders ): Stamp_IC_WooCommerce_Plugin {

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
	public function get_container(): Stamp_IC_WC_DI_Container {
		return $this->container;
	}

	/**
	 * @param Stamp_IC_WC_DI_Container $container
	 *
	 * @return Stamp_IC_WooCommerce_Plugin
	 */
	public function set_container( Stamp_IC_WC_DI_Container $container ): Stamp_IC_WooCommerce_Plugin {
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

//		if( ! $this->is_can_run_checked() ) {
//			$this->can_run();
//		}
//
//		if( ! $this->can_run ) {
//			return;
//		}

		$this->init_loaders();
	}

	public function can_run(): bool {

		/* @var Stamp_IC_WC_Settings_Repository $settings_repository */
//		$settings_repository = $this->get_container()->get( 'Stamp_IC_WC_Settings_Repository' );
//
//		if ( ! filter_var($settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_URL ), FILTER_VALIDATE_URL ) ) {
//
//			$error = sprintf(
//				__(
//					'Invalid Stamp API url. The Instant Checkout for WooCommerce plugin will not work.',
//					STAMP_IC_WC_TEXT_DOMAIN
//				)
//			);
//
//			error_log( $error );
//
//			if( is_admin() ) {
//				add_action( 'admin_notices', function() use ( $error ) {
//					?>
<!--					<div class="notice notice-error">-->
<!--						<p>-->
<!--							--><?php //echo $error ?>
<!--						</p>-->
<!--					</div>-->
<!--					--><?php
//				});
//			}
//
//			$this->can_run = false;
//		}

		$this->set_can_run_checked( true );

		$this->can_run = true;

		return $this->can_run;
	}

	/**
	 * @return bool
	 */
	public function is_can_run_checked(): bool {
		return $this->can_run_checked;
	}

	/**
	 * @param bool $can_run_checked
	 *
	 * @return Stamp_IC_WooCommerce_Plugin
	 */
	public function set_can_run_checked( bool $can_run_checked ): Stamp_IC_WooCommerce_Plugin {
		$this->can_run_checked = $can_run_checked;
		return $this;
	}
}
