<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Stamp_IC_WC_Checkout_Button {

	/* @var Stamp_IC_WC_Settings_Repository $settings_repository */
	protected $settings_repository;

	/**
	 * @return Stamp_IC_WC_Settings_Repository
	 */
	public function get_settings_repository() {
		return $this->settings_repository;
	}

	public function set_settings_repository( Stamp_IC_WC_Settings_Repository $settings_repository ) {
		$this->settings_repository = $settings_repository;
		return $this;
	}

    public function show_checkout_button() {

	    $attributes = array(
		    'class' => array(
			    'stamp-ic-checkout-button',
			    'button',
			    'alt',
			    'checkout',
			    'checkout-button'
		    ),
		    'href' => '#',
		    'type' => 'button',
		    'style' => array(),
	    );

	    if( current_filter() !== 'woocommerce_after_add_to_cart_button' ) {
		    $attributes[ 'class' ][] = 'wc-forward';
	    }

	    $button_color = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_CHECKOUT_BUTTON_COLOR );

	    if( ! empty( $button_color ) ) {
		    $attributes[ 'style' ][ 'background' ] = '#' . $button_color;
	    }

	    $button_inline_css = $this->settings_repository->get( Stamp_IC_WC_Settings_Repository::WC_CHECKOUT_BUTTON_INLINE_CSS );

	    if( ! empty( $button_inline_css ) ) {

		    $button_inline_css = explode( ';', $button_inline_css );

		    if( is_array( $button_inline_css ) ) {

		    	foreach ( $button_inline_css as $css_property ) {

		    		if( empty( $css_property ) ) {
		    			continue;
				    }

				    $css_property = explode( ':', $css_property );

				    if( is_array( $css_property ) && count( $css_property ) === 2 ) {
					    $attributes[ 'style' ][ trim( $css_property[ 0 ] ) ] = trim( $css_property[ 1 ] );
				    }
			    }
		    }
	    }

        $attributes = apply_filters( 'stamp_ic_checkout_button_attributes', $attributes );

        $element = apply_filters( 'stamp_ic_checkout_button_element', is_product() ? 'button' : 'link' ) === 'button' ? 'button' : 'link';

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

            if( $attribute === 'style' ) {

            	$style = '';

            	foreach ( $value as $property => $property_value ) {
		            $style .= esc_attr( $property ) . ':' . esc_attr( $property_value ) . ';';
	            }

            	$value = $style;

            } else {
	            $value = is_array( $value ) ? implode( ' ', $value ) : $value;
            }

			if( ! empty( $value ) ) {
				$attributes_string .= $attribute . '="' . esc_attr( $value ) . '"';
			}
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
	    add_action( 'woocommerce_widget_shopping_cart_buttons', array( $this, 'show_checkout_button' ), 9999 );
	    add_action( 'woocommerce_proceed_to_checkout', array( $this, 'show_checkout_button' ), 9999 );
    }
}
