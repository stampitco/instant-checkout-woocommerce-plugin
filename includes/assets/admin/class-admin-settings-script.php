<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Admin_Settings_Script extends Stamp_IC_WC_Abstract_Script {

	const NAME = 'stampIcWcAdminSettings';

	public function name(): string {
		return static::NAME;
	}

	public function url(): string {
		return STAMP_IC_WC_PLUGIN_URL . '/assets/dist/admin/js/settings.js';
	}

	public function deps(): array {
		return array(
			'jquery',
		);
	}

	public function screens(): array {
		return array(
			'settings_page_stamp-ic-wc',
		);
	}
}
