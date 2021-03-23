<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Stamp_IC_WC_Checkout_Button {

    public function show_checkout_button() {

	    $attributes = array(
		    'class' => array(
			    'woocommerce-button',
			    'button',
			    'alt',
			    'stamp-ic-checkout-button',
		    ),
		    'href' => '#',
		    'type' => 'button',
		    'id' => 'stamp-ic-checkout-button',
	    );

	    if( is_cart()) {
		    $attributes[ 'class' ][] = 'wc-forward';
		    $attributes[ 'class' ][] = 'checkout-button';
	    }

        $attributes = apply_filters( 'stamp_ic_checkout_button_attributes', $attributes );

        $element = apply_filters( 'stamp_ic_checkout_button_element', is_cart() ? 'link' : 'button' ) === 'button' ? 'button' : 'link';

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
