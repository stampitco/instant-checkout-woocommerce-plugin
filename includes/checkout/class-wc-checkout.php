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

    public function get_checkout_url( array $input ): array {

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

	    if( ! empty( $input[ 'fromCart' ] ) ) {

		    $input[ 'items' ] = array();

		    foreach ( WC()->cart->get_cart() as $cart_item ) {

		    	if( ! empty( $cart_item[ 'product_id' ] ) && ! empty( $cart_item[ 'quantity' ] ) ) {

		    		$item = array(
					    'product_id' => $cart_item[ 'product_id' ],
					    'qty' => $cart_item[ 'quantity' ],
				    );

				    if( ! empty( $cart_item[ 'variation_id' ] ) ) {
					    $item[ 'variation_id' ] = $cart_item[ 'variation_id' ];
				    }

				    $input[ 'items' ][] = $item;
			    }
		    }
	    }

	    if( empty( $input[ 'items' ] ) ) {
		    return array(
			    'error' => true,
			    'payload' => array(
				    'message' => __( 'Missing items param', STAMP_IC_WC_TEXT_DOMAIN )
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

        $web_url = STAMP_WEB_URL;

        if( empty( $web_url ) || ! filter_var( $web_url, FILTER_VALIDATE_URL ) ) {
            return array(
                'error' => true,
                'payload' => array(
                    'message' => __( 'Stamp Web URL missing or invalid', STAMP_IC_WC_TEXT_DOMAIN )
                )
            );
        }

	    $instant_checkout_url = untrailingslashit( $web_url );

	    $query_args = array(
		    'appKey' => $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::STAMP_API_KEY ),
		    'products' => []
	    );

        foreach ( $input[ 'items' ] as $item ) {

	        $product_id = $item[ 'product_id' ] ?? null;
	        $variation_id = $item[ 'variation_id' ] ?? null;

	        $product = wc_get_product( $product_id );

	        if( ! $product instanceof WC_Product ) {
		        return array(
			        'error' => true,
			        'payload' => array(
				        'message' => __( 'Product not found', STAMP_IC_WC_TEXT_DOMAIN )
			        )
		        );
	        }

	        $qty = $item[ 'qty' ] ?? null;

	        if( empty( $qty ) ) {
		        return array(
			        'error' => true,
			        'payload' => array(
				        'message' => __( 'Product qty is empty', STAMP_IC_WC_TEXT_DOMAIN )
			        )
		        );
	        }

	        $product_query_param = array(
		        'i' => $product_id,
		        'q' => $qty,
	        );

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

		        if( $variation instanceof WC_Product_Variation ) {
			        $product_query_param[ 'i' ] = $variation_id;
		        }
	        }

	        $query_args[ 'products' ][] = $product_query_param;
        }

        if( ! empty( $query_args[ 'products' ] ) ) {
	        $query_args[ 'products' ] = json_encode( $query_args[ 'products' ] );
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

    public function show_checkout_button() {

        $attributes = apply_filters(
            'stamp_ic_checkout_button_attributes',
            array(
                'class' => array(
                    'woocommerce-button',
                    'button',
                    'alt',
                    'stamp-ic-checkout-button',
                ),
                'href' => '#',
                'type' => 'button',
                'id' => 'stamp-ic-checkout-button',
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

	    echo implode( ' ', $html );
    }

    public function init_checkout_button_display() {

    	$custom_position = apply_filters( 'stamp_ic_checkout_button_custom_position', false );

    	if( $custom_position ) {
		    add_action( 'stamp_ic_checkout_do_checkout_button', array( $this, 'show_checkout_button' ) );
		    return;
	    }

	    add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'show_checkout_button' ) );
	    add_action( 'woocommerce_proceed_to_checkout', array( $this, 'show_checkout_button' ), 999 );
    }
}
