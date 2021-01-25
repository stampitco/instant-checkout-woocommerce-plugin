<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Admin_Settings_Style extends Stamp_IC_WC_Abstract_Style {

	const NAME = 'stampIcWcAdminSettingsStyle';

	public function name(): string {
		return static::NAME;
	}

	public function url(): string {
		return STAMP_IC_WC_PLUGIN_URL . '/assets/dist/admin/css/settings.css';
	}

	public function screens(): array {
		return array(
			'settings_page_stamp-ic-wc',
		);
	}
}
