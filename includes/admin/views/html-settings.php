<?php
/**
 * @var array $notifications
 * @var string $active_tab
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap" id="wc-ic-settings-page">
	<h1>
		<?php _e( 'Instant Checkout', STAMP_IC_WC_TEXT_DOMAIN ) ?>
	</h1>

    <?php if( is_array( $notifications ) ): ?>

        <?php /* @var Stamp_IC_WC_Settings_Notification $notification */  ?>
        <?php foreach( $notifications as $notification ): ?>
            <div class="notice notice-<?php echo esc_attr( $notification->get_type() ); ?> is-dismissible">
                <p>
	                <?php echo esc_html( $notification->get_message() ); ?>
                </p>
                <button type="button" class="notice-dismiss">
                    <span class="screen-reader-text">Dismiss this notice.</span>
                </button>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

    <h2 class="nav-tab-wrapper">
        <a href="<?php echo esc_url( admin_url( 'options-general.php?' . http_build_query( array( 'page' => 'stamp-ic-wc', 'tab' => 'settings' ) ) ) ); ?>" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>">
	        <?php _e( 'Settings', STAMP_IC_WC_TEXT_DOMAIN ); ?>
        </a>
        <a href="<?php echo esc_url( admin_url( 'options-general.php?' . http_build_query( array( 'page' => 'stamp-ic-wc', 'tab' => 'styling' ) ) ) ); ?>" class="nav-tab <?php echo $active_tab == 'styling' ? 'nav-tab-active' : ''; ?>">
	        <?php _e( 'Styling', STAMP_IC_WC_TEXT_DOMAIN ); ?>
        </a>
    </h2>

    <?php if( $active_tab === 'settings' ): ?>
        <?php include __DIR__ . '/html-settings-tab.php'; ?>
    <?php endif; ?>

	<?php if( $active_tab === 'styling' ): ?>
		<?php include __DIR__ . '/html-styling-tab.php'; ?>
	<?php endif; ?>

</div>