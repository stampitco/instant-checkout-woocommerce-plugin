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

    public function get_checkout_url() {

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
                'href' => '#'
            )
        );

        $element = apply_filters( 'stamp_ic_checkout_button_element', 'button' ) === 'button' ? 'button' : 'link';

        if( $element === 'button' && ! empty( $attributes[ 'href' ] ) ) {
            unset( $attributes[ 'href' ] );
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
