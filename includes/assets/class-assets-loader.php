<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Assets_Loader extends Stamp_IC_WooCommerce_Abstract_Loader {

	protected $admin_scripts = array();

	protected $admin_styles = array();

	protected $public_scripts = array();

	protected $public_styles = array();

	public function run() {
		if( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		}
		if( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_assets' ) );
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
			$this->add_script( $script, true );
		}
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
			$this->add_style( $style, true );
		}
		return $this;
	}

	/**
	 * @return array
	 */
	public function get_public_scripts(): array {
		return $this->public_scripts;
	}

	/**
	 * @param array $public_scripts
	 *
	 * @return Stamp_IC_WC_Assets_Loader
	 */
	public function set_public_scripts( array $public_scripts ): Stamp_IC_WC_Assets_Loader {
		foreach ( $public_scripts as $script ) {
			$this->add_script( $script );
		}
		return $this;
	}

	/**
	 * @return array
	 */
	public function get_public_styles(): array {
		return $this->public_styles;
	}

	/**
	 * @param array $public_styles
	 *
	 * @return Stamp_IC_WC_Assets_Loader
	 */
	public function set_public_styles( array $public_styles ): Stamp_IC_WC_Assets_Loader {
		foreach ( $public_styles as $style ) {
			$this->add_style( $style );
		}
		return $this;
	}

	public function enqueue_admin_assets() {
		/* @var Stamp_IC_WC_Abstract_Script $script */
		foreach ( $this->get_admin_scripts() as $script ) {
			$this->enqueue_script( $script, true );
		}
		/* @var Stamp_IC_WC_Abstract_Style $style */
		foreach ( $this->get_admin_styles() as $style ) {
			$this->enqueue_style( $style, true );
		}
	}

	public function enqueue_public_assets() {
		/* @var Stamp_IC_WC_Abstract_Script $script */
		foreach ( $this->get_public_scripts() as $script ) {
			$this->enqueue_script( $script );
		}
		/* @var Stamp_IC_WC_Abstract_Style $style */
		foreach ( $this->get_public_styles() as $style ) {
			$this->enqueue_style( $style );
		}
	}

	public function add_script( Stamp_IC_WC_Abstract_Script $script, $in_admin = false ): Stamp_IC_WC_Assets_Loader {
		if( $in_admin && ! in_array( $script, $this->admin_scripts ) ) {
			$this->admin_scripts[] = $script;
		}
		if( ! $in_admin && ! in_array( $script, $this->public_scripts ) ) {
			$this->public_scripts[] = $script;
		}
		return $this;
	}

	public function add_style( Stamp_IC_WC_Abstract_Style $style, $in_admin = false ): Stamp_IC_WC_Assets_Loader {
		if( $in_admin && ! in_array( $style, $this->admin_scripts ) ) {
			$this->admin_styles[] = $style;
		}
		if( ! $in_admin && ! in_array( $style, $this->public_styles ) ) {
			$this->public_styles[] = $style;
		}
		return $this;
	}

	public function enqueue_script( Stamp_IC_WC_Abstract_Script $script, $in_admin = false ): Stamp_IC_WC_Assets_Loader {
		if( apply_filters( 'stamp_ic_wc_should_enqueue_script', $script->should_enqueue(), $script ) ) {

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
		return $this;
	}

	public function enqueue_style( Stamp_IC_WC_Abstract_Style $style, $in_admin = false ): Stamp_IC_WC_Assets_Loader {
		if( apply_filters( 'stamp_ic_wc_should_enqueue_style', $style->should_enqueue(), $style ) ) {
			wp_enqueue_style(
				$style->name(),
				$style->url(),
				$style->deps(),
				$style->version(),
				$style->media()
			);
		}
		return $this;
	}
}