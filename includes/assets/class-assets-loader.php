<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Assets_Loader extends Stamp_IC_WooCommerce_Abstract_Loader {

	protected $admin_scripts = array();

	protected $admin_styles = array();

	public function run() {
		if( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		}
	}

	/**
	 * @return array
	 */
	public function get_admin_scripts(): array {
		return $this->admin_scripts;
	}

	public function set_admin_scripts( array $admin_scripts ): Stamp_IC_WC_Assets_Loader {

		foreach ( $admin_scripts as $script ) {
			if( ! $script instanceof Stamp_IC_WC_Abstract_Script ) {
				throw new RuntimeException(
					sprintf(
						'Class %s must be an instance of %s',
						get_class( $script ),
						'Stamp_IC_WC_Abstract_Script'
					)
				);
			}
		}

		$this->admin_scripts = $admin_scripts;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_admin_styles(): array {
		return $this->admin_styles;
	}

	public function set_admin_styles( array $admin_styles ): Stamp_IC_WC_Assets_Loader {

		foreach ( $admin_styles as $style ) {
			if( ! $style instanceof Stamp_IC_WC_Abstract_Style ) {
				throw new RuntimeException(
					sprintf(
						'Class %s must be an instance of %s',
						get_class( $style ),
						'Stamp_IC_WC_Abstract_Style'
					)
				);
			}
		}

		$this->admin_styles = $admin_styles;

		return $this;
	}

	public function enqueue_admin_assets() {

		$screen = get_current_screen();

		/* @var Stamp_IC_WC_Abstract_Script $script */
		foreach ( $this->get_admin_scripts() as $script ) {
			if( $screen instanceof WP_Screen && in_array( $screen->id, $script->screens() ) ) {

				wp_enqueue_script(
					$script->name(),
					$script->url(),
					$script->deps(),
					$script->version(),
					$script->in_footer()
				);

				$data = $script->data();

				if( ! empty( $data ) ) {
					wp_localize_script( $script->name(), $script->name(), $data );
				}
			}
		}

		/* @var Stamp_IC_WC_Abstract_Style $style */
		foreach ( $this->get_admin_styles() as $style ) {
			if( $screen instanceof WP_Screen && in_array( $screen->id, $style->screens() ) ) {
				wp_enqueue_style(
					$style->name(),
					$style->url(),
					$style->deps(),
					$style->version(),
					$style->media()
				);
			}
		}
	}
}