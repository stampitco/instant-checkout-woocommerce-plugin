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
        checkoutParams,
    } = params;

    this.$button = $button;
    this.api = api;
    this.debug = debug;
    this.mediator = mediator;
    this.checkoutParams = checkoutParams;

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

    const params = this.checkoutParams.get();

    if( ! params ) {
        this.disableButtonLoading();
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

export default Checkout;