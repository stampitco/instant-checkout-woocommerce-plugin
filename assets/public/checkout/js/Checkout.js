import $ from 'jquery';

import {
    GET_CHECKOUT_URL_STARTED,
    GET_CHECKOUT_URL_SUCCESS,
    GET_CHECKOUT_URL_ERROR,
    CHECKOUT_WINDOW_CLOSED,
} from './events';

const Checkout = function Checkout( params ) {

    const {
        $element,
        api,
        debug,
        checkoutWindow
    } = params;

    this.$element = $element;
    this.api = api;
    this.checkoutWindow = checkoutWindow;
    this.debug = debug;

    this.init();
}

Checkout.prototype.init = function init() {
    this.bindEvents();
};

Checkout.prototype.bindEvents = function bindEvents() {
    this.$element.click( this.onCheckoutButtonClick.bind( this ) );
    this.$element.on( GET_CHECKOUT_URL_ERROR, this.onGetCheckoutUrlError.bind( this ) );
    $(document).on( CHECKOUT_WINDOW_CLOSED, this.onGetCheckoutWindowClosed.bind( this ) );
};

Checkout.prototype.onGetCheckoutWindowClosed = async function onGetCheckoutWindowClosed( event, $invoker ) {
    if( $invoker.is( this.$element ) ) {
        this.enableButton();
    }
}

Checkout.prototype.onGetCheckoutUrlError = async function onGetCheckoutUrlError( event ) {
    if( $(event.target).is( this.$element ) ) {
        this.enableButton();
    }
}

Checkout.prototype.onCheckoutButtonClick = async function onCheckoutButtonClick( event ) {

    event.preventDefault();

    if( this.isOnSingleProductPage() ) {

        const $cartForm = this.$element.parents('form.cart');

        if( $cartForm.length === 0 ) {
            if( this.debug ) {
                console.error( 'Instant Checkout: Cart form html element missing' );
            }
            return;
        }

        const data = {
            product_id: this.$element.data( 'product_id' ),
            qty: parseInt( $cartForm.find( 'input[name="quantity"]' ).val() ),
        };

        if( ! data.qty ) {

            if( this.debug ) {
                console.error( 'Instant Checkout: Product Qty is empty' );
            }

            this.$element.trigger(
                GET_CHECKOUT_URL_ERROR,
                [
                    data,
                    {
                        error: {
                            qty: 'stamp_ic_checkout_get_checkout_url_qty_empty'
                        }
                    }
                ]
            );

            return;
        }

        if( $cartForm.hasClass( 'variations_form' ) ) {

            data[ 'variation_id' ] = parseInt( $cartForm.find( 'input[name="variation_id"]' ).val() )

            if( ! data.variation_id ) {

                if( this.debug ) {
                    console.error( 'Instant Checkout: Product Variation is empty' );
                }

                this.$element.trigger(
                    GET_CHECKOUT_URL_ERROR,
                    [
                        data,
                        {
                            error: {
                                variation_id: 'stamp_ic_checkout_get_checkout_url_variation_id_empty'
                            }
                        }
                    ]
                );

                return;
            }
        }

        this.$element.attr( { disabled: true } );
        this.$element.addClass( 'stamp-ic-checkout-loading' );
        this.$element.trigger( GET_CHECKOUT_URL_STARTED, [ data ] );

        this.checkoutWindow.open( {
            product_id: data.product_id,
            $invoker: this.$element,
            title: '_blank',
            params: 'scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=400,height=900'
        } );

        try {
            const result = await this.api.getCheckoutUrl( data );
            this.$element.trigger( GET_CHECKOUT_URL_SUCCESS, [ data, result ] );
            this.checkoutWindow.setUrl( result.checkout_url );
        } catch ( error ) {
            this.$element.trigger( GET_CHECKOUT_URL_ERROR, [ data, error ] );
            if( this.debug ) {
                console.error( 'Instant Checkout: Failed to get the checkout url from the backend' + error );
            }
            this.checkoutWindow.close();
        }

        this.$element.removeClass( 'stamp-ic-checkout-loading' );
    }
};

Checkout.prototype.disableButton = function disableButton() {
    this.$element.attr( { disabled: true } );
};

Checkout.prototype.enableButton = function enableButton() {
    this.$element.attr( { disabled: false } );
};

Checkout.prototype.isOnSingleProductPage = function isOnSingleProductPage() {
    const $body = $('body');
    return $body.hasClass('single-product') && $body.hasClass('woocommerce')
};

export default Checkout;