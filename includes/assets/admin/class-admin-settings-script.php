<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Admin_Settings_Script extends Stamp_IC_WC_Abstract_Script {

	const NAME = 'stampIcWcAdminSettings';

	public function name() {
		return static::NAME;
	}

	public function url() {
		return STAMP_IC_WC_PLUGIN_URL . '/assets/dist/admin/js/settings.js';
	}

	public function deps() {
		return array(
			'jquery',
			'wp-theme-plugin-editor'
		);
	}

	public function should_enqueue() {
		$screen = get_current_screen();
		return $screen instanceof WP_Screen && in_array( $screen->id, array(
			'settings_page_stamp-ic-wc',
		) );
	}

	public function data( array $params = array() ) {
		return array(
			'inlineCssEditorSettings' => wp_enqueue_code_editor( array(
				'codemirror' => array(
					'mode' => 'text/css',
				),
			) )
		);
	}
}
