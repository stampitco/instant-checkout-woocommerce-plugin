<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Activator {

	protected $self_plugin_handle = 'instant-checkout-woocommerce-plugin/instant-checkout-woocommerce.php';

	protected $required_plugins = array(
		'woocommerce/woocommerce.php' => 'WooCommerce',
	);

	public function activate( array $active_plugins ) {

		$missing_plugins = $this->check_for_required_plugins( $active_plugins );

		if( ! empty( $missing_plugins ) ) {
			array_map( array( $this, 'report_missing_plugin' ), $missing_plugins );
			return false;
		}

		return true;
	}

	public function check_for_required_plugins( array $active_plugins ) {

		$missing_plugins = $this->required_plugins;

		foreach ( $active_plugins as $plugin_handle ) {

			if( $plugin_handle === $this->self_plugin_handle ) {
				continue;
			}

			if( array_key_exists( $plugin_handle, $this->required_plugins ) ) {
				unset( $missing_plugins[ $plugin_handle ] );
			}
		}

		return $missing_plugins;
	}

	public function report_missing_plugin( $name ) {

		if( array_key_exists( $name, $this->required_plugins ) ) {
			$name = $this->required_plugins[ $name ];
		}

		$error = sprintf(
			__(
				'Some of the required plugins are not installed or active. The Instant Checkout for WooCommerce plugin will not work without them:',
				STAMP_IC_WC_TEXT_DOMAIN
			) . ' %s.',
			$name
		);

		error_log( $error );

		if( is_admin() ) {
			add_action( 'admin_notices', function() use ( $error ) {
				?>
				<div class="notice notice-error">
					<p>
						<?php echo $error ?>
					</p>
				</div>
				<?php
			});
		}

		return $name;
	}
}
