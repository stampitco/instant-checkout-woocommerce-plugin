import $ from 'jquery';

import {
    CHECKOUT_WINDOW_CLOSED,
    GET_CHECKOUT_URL_ERROR,
    GET_CHECKOUT_URL_STARTED,
    GET_CHECKOUT_URL_SUCCESS,
} from './events';

const Checkout = function Checkout( params ) {

    const {
        $button,
        api,
        debug,
        mediator,
    } = params;

    this.$button = $button;
    this.api = api;
    this.debug = debug;
    this.mediator = mediator;

    this.init();
}

Checkout.prototype.init = function init() {
    this.mediator.subscribe( CHECKOUT_WINDOW_CLOSED, this.onCheckoutWindowClosed.bind( this ) );
    this.bindEvents();
};

Checkout.prototype.bindEvents = function bindEvents() {
    this.$button.click( this.onCheckoutButtonClick.bind( this ) );
};

Checkout.prototype.onCheckoutWindowClosed = function onCheckoutWindowClosed() {
    this.disableButtonLoading();
};

Checkout.prototype.onCheckoutButtonClick = async function onCheckoutButtonClick( event ) {

    event.preventDefault();

    this.enableButtonLoading();

    const params = this.getCheckoutParams();

    if( ! params ) {
        return;
    }

    this.$button.trigger( GET_CHECKOUT_URL_STARTED, [ params ] );
    this.mediator.publish( GET_CHECKOUT_URL_STARTED, params );

    try {
        const result = await this.api.getCheckoutUrl( params );
        this.$button.trigger( GET_CHECKOUT_URL_SUCCESS, [ params, result ] );
        this.mediator.publish( GET_CHECKOUT_URL_SUCCESS, params, result );
    } catch ( error ) {
        this.$button.trigger( GET_CHECKOUT_URL_ERROR, [ params, error ] );
        this.disableButtonLoading();
        if( this.debug ) {
            console.error( 'Instant Checkout: Failed to get the checkout url from the backend' + error );
        }
    }
}

Checkout.prototype.disableButtonLoading = function disableButtonLoading() {
    this.$button.attr( { disabled: false } ).removeClass( 'stamp-ic-checkout-loading' );
};

Checkout.prototype.enableButtonLoading = function enableButtonLoading() {
    this.$button.attr( { disabled: true } ).addClass( 'stamp-ic-checkout-loading' );
};

Checkout.prototype.getCheckoutParams = function getCheckoutParams() {

    const $cartForm = this.$button.parents('form.cart');

    if( $cartForm.length === 0 ) {
        if( this.debug ) {
            console.error( 'Instant Checkout: Cart form html element missing' );
        }
        return false;
    }

    const qty = parseInt( $cartForm.find( 'input[name="quantity"]' ).val() );

    if( ! qty ) {
        if( this.debug ) {
            console.error( 'Instant Checkout: Product Qty is empty' );
        }
        this.$button.trigger(
            GET_CHECKOUT_URL_ERROR,
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
        return false;
    }

    const data = {
        product_id: this.$button.data( 'product_id' ),
        qty,
    };

    if( $cartForm.hasClass( 'variations_form' ) ) {

        const variation_id = parseInt( $cartForm.find( 'input[name="variation_id"]' ).val() );

        if( ! variation_id ) {
            if( this.debug ) {
                console.error( 'Instant Checkout: Product Variation is empty' );
            }
            this.$button.trigger(
                GET_CHECKOUT_URL_ERROR,
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
    }

    return data;
};

Checkout.prototype.isOnSingleProductPage = function isOnSingleProductPage() {
    const $body = $('body');
    return $body.hasClass('single-product') && $body.hasClass('woocommerce')
};

export default Checkout;