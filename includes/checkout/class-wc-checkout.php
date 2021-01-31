<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Stamp_IC_WC_Checkout {

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
     * @return Stamp_IC_WC_Checkout
     */
    public function set_settings_repository( Stamp_IC_WC_Settings_Repository $settings_repository ): Stamp_IC_WC_Checkout {
        $this->settings_repository = $settings_repository;
        return $this;
    }

    public function get_checkout_url_ajax_handler() {

        $result = $this->get_checkout_url( $_POST );

        if( ! empty( $result[ 'error' ] ) && $result[ 'error' ] === true ) {
            wp_send_json_error( $result[ 'payload' ], 400 );
        }

        wp_send_json_success( $result[ 'payload' ] );
    }

    public function get_checkout_url( array $input ) {

        if( empty( $input[ 'stamp-ic-checkout' ] ) ) {
            return array(
                'error' => true,
                'payload' => array(
                    'message' => __( 'Missing nonce param', STAMP_IC_WC_TEXT_DOMAIN )
                )
            );
        }

        if ( ! wp_verify_nonce( $input[ 'stamp-ic-checkout' ], 'stamp-ic-checkout' ) ) {
            return array(
                'error' => true,
                'payload' => array(
                    'message' => __( 'You are not authorized to perform this action', STAMP_IC_WC_TEXT_DOMAIN )
                )
            );
        }

        $app_key = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY );

        if( empty( $app_key ) ) {
            return array(
                'error' => true,
                'payload' => array(
                    'message' => __( 'Stamp App key missing', STAMP_IC_WC_TEXT_DOMAIN )
                )
            );
        }

        $api_url = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_URL );

        if( empty( $api_url ) || ! filter_var( $api_url, FILTER_VALIDATE_URL ) ) {
            return array(
                'error' => true,
                'payload' => array(
                    'message' => __( 'Stamp Api URL missing or invalid', STAMP_IC_WC_TEXT_DOMAIN )
                )
            );
        }

        $product_id = $input[ 'product_id' ] ?? null;
        $variation_id = $input[ 'variation_id' ] ?? null;

        $product = wc_get_product( $product_id );
        $variation = null;

        if( ! $product instanceof WC_Product ) {
            return array(
                'error' => true,
                'payload' => array(
                    'message' => __( 'Product not found', STAMP_IC_WC_TEXT_DOMAIN )
                )
            );
        }

        if( $product->is_type( 'variable' ) ) {

            if( is_null( $variation_id ) || ! in_array( $variation_id, $product->get_children() ) ) {
                return array(
                    'error' => true,
                    'payload' => array(
                        'message' => __( 'Variation does not belong to this product', STAMP_IC_WC_TEXT_DOMAIN )
                    )
                );
            }

            $variation = wc_get_product( $variation_id );
        }

        $qty = $input[ 'qty' ] ?? null;

        if( empty( $qty ) ) {
            return array(
                'error' => true,
                'payload' => array(
                    'message' => __( 'Product qty is empty', STAMP_IC_WC_TEXT_DOMAIN )
                )
            );
        }

        $instant_checkout_url = untrailingslashit(
            $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_URL )
        );

        $query_args = array(
            'productId' => $product_id,
            'productSku' => $product->get_sku(),
            'quantity' => $qty,
            'appKey' => $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY ),
        );

        if( $variation instanceof WC_Product_Variation ) {
            $query_args[ 'variationId' ] = $variation_id;
            $query_args[ 'variationSku' ] = $variation->get_sku();
        }

        return array(
            'error' => false,
            'payload' => array(
                'checkout_url' => sprintf(
                    '%s/checkout?%s',
                    $instant_checkout_url,
                    http_build_query( $query_args )
                )
            )
        );
    }

    public function show_checkout_button( $product_id, array $params = array(), $output = true ): ?array {

        $attributes = apply_filters(
            'stamp_ic_checkout_button_attributes',
            array(
                'data-product_id' => $product_id,
                'class' => array(
                    'woocommerce-button',
                    'woocommerce-Button',
                    'button',
                    'stamp-ic-checkout-button',
                ),
                'id' => 'stamp-ic-checkout-button-' . $product_id,
                'href' => '#',
                'type' => 'button',
            )
        );

        $element = apply_filters( 'stamp_ic_checkout_button_element', 'button' ) === 'button' ? 'button' : 'link';

        if( $element === 'button' && ! empty( $attributes[ 'href' ] ) ) {
            unset( $attributes[ 'href' ] );
        }

        if( $element === 'link' && ! empty( $attributes[ 'type' ] ) ) {
            unset( $attributes[ 'type' ] );
        }

        $attributes_string = '';

        foreach ( $attributes as $attribute => $value ) {
            if( ! empty( $attributes_string ) ) {
                $attributes_string .= ' ';
            }
            $attributes_string .= $attribute . '="' . esc_attr( is_array( $value ) ? implode( ' ', $value ) : $value ) . '"';
        }

        $html = array(
            sprintf(
                '<%s %s>',
                $element === 'button' ? 'button' : 'a',
                $attributes_string
            )
        );

        $html[] = apply_filters( 'stamp_ic_checkout_button_text', __( 'Instant Checkout', STAMP_IC_WC_TEXT_DOMAIN ) ) ;

        $html[] = sprintf(
            '</%s>',
            $element === 'button' ? 'button' : 'a'
        );

        $html = apply_filters( 'stamp_ic_checkout_button_html', $html );

        if( $output === true ) {
            echo implode( ' ', $html );
            return null;
        }

        return $html;
    }
}
