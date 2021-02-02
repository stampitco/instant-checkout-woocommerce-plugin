<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Stamp_IC_WC_Webhooks {

    const WEBHOOK_API_VERSION = 'wp_api_v3';

    /* @var Stamp_IC_WC_Settings_Repository $settings_repository */
    protected $settings_repository;

    /**
     * @return Stamp_IC_WC_Settings_Repository
     */
    public function get_settings_repository(): Stamp_IC_WC_Settings_Repository {
        return $this->settings_repository;
    }

    /**
     * @param Stamp_IC_WC_Settings_Repository $settings_repository
     *
     * @return Stamp_IC_WC_Webhooks
     */
    public function set_settings_repository( Stamp_IC_WC_Settings_Repository $settings_repository ): Stamp_IC_WC_Webhooks {
        $this->settings_repository = $settings_repository;
        return $this;
    }

    public function save_wc_webhooks( array $params ) {
        if( ! empty( $params[ 'user_id' ] ) ) {

            if( array_key_exists( 'related_webhook_order_updated_id', $params ) ) {

                $webhook_order_updated_id = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_UPDATED_ID );

                if( empty( $webhook_order_updated_id ) || (int) $params[ 'related_webhook_order_updated_id' ] !== (int) $webhook_order_updated_id ) {

                    $webhook = $this->create_webhook( 'order.updated', $params[ 'user_id' ] );

                    if( $webhook instanceof WC_Webhook ) {
                        $this->settings_repository->set( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_UPDATED_ID, $webhook->get_id() );
                    }
                }
            }

            if( array_key_exists( 'related_webhook_order_deleted_id', $params ) ) {

                $webhook_order_deleted_id = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_DELETED_ID );

                if( empty( $webhook_order_deleted_id ) || (int) $params[ 'related_webhook_order_deleted_id' ] !== (int) $webhook_order_deleted_id ) {

                    $webhook = $this->create_webhook( 'order.deleted', $params[ 'user_id' ] );

                    if( $webhook instanceof WC_Webhook ) {
                        $this->settings_repository->set( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_DELETED_ID, $webhook->get_id() );
                    }
                }
            }
        }
    }

    public function process_webhook_http_params( array $http_args, $arg, $webhook_id ): array {

        $webhook_id = (int) $webhook_id;

        $webhook_order_updated_id = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_UPDATED_ID );
        $webhook_order_deleted_id = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_WEBHOOK_ORDER_DELETED_ID );

        if( (int) $webhook_order_updated_id === $webhook_id || (int) $webhook_order_deleted_id === $webhook_id ) {
            $http_args['headers']['X-IC-AppKey'] = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY );
        }

        return $http_args;
    }

    public function create_webhook( $topic, $user_id ): ?WC_Webhook {

        if( ! wc_is_webhook_valid_topic( $topic ) ) {
            return null;
        }

        $delivery_url = STAMP_API_URL . '/api/webhooks/woocommerce';

        if ( ! wc_is_valid_url( $delivery_url ) ) {
            return null;
        }

        $webhook = new WC_Webhook( 0 );

        $webhook->set_name( $topic === 'order.updated' ? 'Stamp API Order Update' : 'Stamp API Order Delete' );
        $webhook->set_user_id( $user_id );
        $webhook->set_status( 'active' );
        $webhook->set_delivery_url( esc_url_raw( wp_unslash( $delivery_url ) ) );
        $webhook->set_secret( wp_generate_password( 50, true, true ) );
        $webhook->set_topic( $topic );
        $webhook->set_api_version( 'wp_api_v3' );

        $webhook->save();

        do_action( 'woocommerce_webhook_options_save', $webhook->get_id() );

        return $webhook;
    }
}
