import $ from 'jquery';

import {
    GET_CHECKOUT_PARAMS_ERROR,
} from './events';

/**
 * CheckoutParams constructor
 *
 * @param {Options} options
 * @param {jQuery} $button
 */
const CheckoutParams = function CheckoutParams( options, $button ) {
    this.options = options;
    this.$button = $button;
}

CheckoutParams.prototype.get = function get() {
    if( this.options.isProductPage() ) {
        return this.getFromProductPage();
    }
    if( this.options.isCartPage() ) {
        return this.getFromCartPage();
    }
    return null;
};

CheckoutParams.prototype.getFromProductPage = function getFromProductPage() {

    const $form = $('form.cart');

    if( $form.length === 0 ) {
        console.error( 'Instant Checkout: Add to Cart form html element missing' );
        return null;
    }

    const qty = parseInt( $form.find( 'input[name="quantity"]' ).val() );

    if( ! qty ) {
        console.error( 'Instant Checkout: Product Qty is empty' );
        this.$button.trigger(
            GET_CHECKOUT_PARAMS_ERROR,
            [
                {
                    errors: [
                        {
                            param: 'qty',
                            message: 'stamp_ic_wc_qty_param_empty'
                        }
                    ]
                }
            ]
        );
        return null;
    }

    const data = {
        qty,
    };

    if( $form.hasClass( 'variations_form' ) ) {

        const variation_id = parseInt( $form.find( 'input[name="variation_id"]' ).val() );

        if( ! variation_id ) {
            console.error( 'Instant Checkout: Product Variation is empty' );
            this.$button.trigger(
                GET_CHECKOUT_PARAMS_ERROR,
                [
                    {
                        errors: [
                            {
                                param: 'variation_id',
                                message: 'stamp_ic_wc_variation_id_param_empty'
                            }
                        ]
                    }
                ]
            );
            return false;
        }

        data[ 'variation_id' ] = variation_id;
        data[ 'product_id' ] = parseInt( $form.find( 'input[name="variation_id"]' ).val() );
    }

    data[ 'product_id' ] = parseInt( $form.find( '[name="add-to-cart"]' ).val() );

    return {
        items: [ data ]
    };
};

CheckoutParams.prototype.getFromCartPage = function getFromCartPage() {
    return {
        fromCart: true
    }
};

export default CheckoutParams;