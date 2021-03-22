import {
    CHECKOUT_WINDOW_CLOSED,
    GET_CHECKOUT_URL_ERROR,
    GET_CHECKOUT_URL_STARTED,
    GET_CHECKOUT_URL_SUCCESS,
    CHECKOUT_ORDER_PLACED,
} from './events';

/**
 * Checkout constructor
 *
 * @param {Options} options
 * @param {jQuery} $button
 * @param {Api} api
 * @param {Mediator} mediator
 * @param {CheckoutParams} checkoutParams
 */
const Checkout = function Checkout( options, $button, api, mediator, checkoutParams ) {
    this.options = options;
    this.$button = $button;
    this.api = api;
    this.mediator = mediator;
    this.checkoutParams = checkoutParams;
    this.init();
}

Checkout.prototype.init = function init() {
    this.mediator.subscribe( CHECKOUT_WINDOW_CLOSED, this.onCheckoutWindowClosed.bind( this ) );
    this.mediator.subscribe( CHECKOUT_ORDER_PLACED, this.onCheckoutOrderPlaced.bind( this ) );
    this.bindEvents();
};

Checkout.prototype.bindEvents = function bindEvents() {
    this.$button.click( this.onCheckoutButtonClick.bind( this ) );
};

Checkout.prototype.onCheckoutOrderPlaced = async function onCheckoutOrderPlaced() {

    this.$button.addClass( 'stamp-ic-checkout-button-order-done' );
    this.$button.attr( { disabled: true } );
    this.$button.text( this.options.getOrderDoneText() );

    if( this.options.isCartPage() ) {
        try {
            const result = await this.api.clearCart( {} );
        } catch ( error ) {
            console.error( 'Instant Checkout: Failed to clear cart contents: ' + error );
        }
    }

    setTimeout( () => {
        this.$button.attr( { disabled: false } );
        this.$button.text( this.options.getInstantButtonText() );
        this.$button.removeClass( 'stamp-ic-checkout-button-order-done' );
        if( this.options.isCartPage() ) {
            window.location.reload();
        }
    }, 3000 );
};

Checkout.prototype.onCheckoutWindowClosed = function onCheckoutWindowClosed() {

    if( ! this.$button.hasClass( 'stamp-ic-checkout-button-order-done' ) ) {
        this.$button.attr( { disabled: false } );
    }

    this.$button.removeClass( 'stamp-ic-checkout-loading' );
};

Checkout.prototype.onCheckoutButtonClick = async function onCheckoutButtonClick( event ) {

    event.preventDefault();

    this.$button.attr( { disabled: true } ).addClass( 'stamp-ic-checkout-loading' );

    const params = this.checkoutParams.get();

    if( ! params ) {
        this.$button.attr( { disabled: false } ).removeClass( 'stamp-ic-checkout-loading' );
        return;
    }

    this.$button.trigger( GET_CHECKOUT_URL_STARTED, [ params ] );
    this.mediator.publish( GET_CHECKOUT_URL_STARTED, params );

    const data = {};

    if(params.hasOwnProperty('fromCart')) {
        data['fromCart'] = true;
    }

    if(params.hasOwnProperty('items')) {
        data['items'] = params.items;
    }

    try {
        const result = await this.api.getCheckoutUrl( data );
        this.$button.trigger( GET_CHECKOUT_URL_SUCCESS, [ params, result ] );
        this.mediator.publish( GET_CHECKOUT_URL_SUCCESS, params, result );
    } catch ( error ) {
        this.$button.trigger( GET_CHECKOUT_URL_ERROR, [ params, error ] );
        this.mediator.publish( GET_CHECKOUT_URL_ERROR, params, error );
        this.$button.removeClass( 'stamp-ic-checkout-loading' );
        console.error( 'Instant Checkout: Failed to get the checkout url from the backend' + error );
    }
}

export default Checkout;