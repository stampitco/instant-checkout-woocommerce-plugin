import $ from 'jquery';

import {
    CHECKOUT_WINDOW_OPENED,
    CHECKOUT_WINDOW_CLOSED,
    CHECKOUT_WINDOW_FOCUSED,
    GET_CHECKOUT_URL_STARTED,
    GET_CHECKOUT_URL_SUCCESS,
    GET_CHECKOUT_URL_ERROR,
    CHECKOUT_ORDER_PLACED,
    CHECKOUT_ORDER_CANCELED,
    CHECKOUT_ORDER_NOT_COMPLETED,
} from './events';

/**
 * CheckoutWindow constructor
 *
 * @param {Options} options
 * @param {Mediator} mediator
 */
const CheckoutWindow = function CheckoutWindow( options, mediator ) {
    this.options = options;
    this.mediator = mediator;
    this.popup = null;
    this.monitorInterval = null;
    this.init();
}

CheckoutWindow.prototype.init = function init() {
    this.mediator.subscribe( GET_CHECKOUT_URL_STARTED, this.onGetCheckoutUrlStarted.bind( this ) );
    this.mediator.subscribe( GET_CHECKOUT_URL_ERROR, this.onGetCheckoutUrlError.bind( this ) );
    this.mediator.subscribe( GET_CHECKOUT_URL_SUCCESS, this.onGetCheckoutUrlSuccess.bind( this ) );
    this.mediator.subscribe( CHECKOUT_WINDOW_FOCUSED, this.onCheckoutWindowFocused.bind( this ) );
    const eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    const messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message";
    window[eventMethod](messageEvent,this.onMessageFromPopUpReceived.bind( this ) );
};

CheckoutWindow.prototype.onGetCheckoutUrlStarted = function onGetCheckoutUrlStarted() {
    this.open({ url: null });
};

CheckoutWindow.prototype.onGetCheckoutUrlError = function onGetCheckoutUrlError() {
    this.close();
};

CheckoutWindow.prototype.onMessageFromPopUpReceived = function onMessageFromPopUpReceived( event ) {

    const { data, origin } = event;

    if( this.popup && origin === this.options.getPopUpUrl() ) {
        try {
            const { message } = JSON.parse(data);
            switch( message ) {
                case 'ORDER_PLACED':
                    this.mediator.publish( CHECKOUT_ORDER_PLACED );
                    break;
                case 'ORDER_CANCELED':
                    this.mediator.publish( CHECKOUT_ORDER_CANCELED );
                    break;
                case 'ORDER_NOT_COMPLETED':
                    this.mediator.publish( CHECKOUT_ORDER_NOT_COMPLETED );
                    break;
            }
        } catch( error ) {
            console.error( error );
        }
    }
};

CheckoutWindow.prototype.onCheckoutWindowFocused = function onCheckoutWindowFocused() {
    if( this.popup ) {
        this.popup.focus();
    }
};

CheckoutWindow.prototype.onGetCheckoutUrlSuccess = function onGetCheckoutUrlSuccess( params, result ) {
    this.setUrl( result.checkout_url );
};

CheckoutWindow.prototype.open = function open( { url } ) {
    if( ! this.popup ) {
        const params = 'scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=400,height=900';
        this.popup = window.open( url || this.options.getPopUpTempUrl(), '_blank', params );
        $( document ).trigger( CHECKOUT_WINDOW_OPENED );
        this.monitorInterval = window.setInterval( this.monitor.bind(this), 300 );
    }
};

CheckoutWindow.prototype.monitor = function monitor() {
    if( this.monitorInterval ) {
        try {
            if ( this.popup == null || this.popup.closed ) {
                window.clearInterval( this.monitorInterval );
                this.close();
            }
        }
        catch ( error ) {
            console.error( error );
        }
    }
};

CheckoutWindow.prototype.setUrl = function setUrl( url ) {
    if( this.popup ) {
        this.popup.location.href = url;
    }
};

CheckoutWindow.prototype.close = function close() {
    if( this.popup ) {
        this.popup.close();
        $( document ).trigger( CHECKOUT_WINDOW_CLOSED );
        this.mediator.publish( CHECKOUT_WINDOW_CLOSED );
        this.popup = null;
    }
};

export default CheckoutWindow;