import $ from 'jquery';

import {
    CHECKOUT_WINDOW_OPENED,
    CHECKOUT_WINDOW_CLOSED,
    CHECKOUT_WINDOW_FOCUSED,
    GET_CHECKOUT_URL_STARTED,
    GET_CHECKOUT_URL_SUCCESS,
    GET_CHECKOUT_URL_ERROR,
} from './events';

const CheckoutWindow = function CheckoutWindow( { $invoker, mediator } ) {
    this.mediator = mediator;
    this.$invoker = $invoker;
    this.popup = null;
    this.monitorInterval = null;
    this.init();
}

CheckoutWindow.prototype.init = function init() {
    this.mediator.subscribe( GET_CHECKOUT_URL_STARTED, this.onGetCheckoutUrlStarted.bind( this ) );
    this.mediator.subscribe( GET_CHECKOUT_URL_ERROR, this.onGetCheckoutUrlError.bind( this ) );
    this.mediator.subscribe( GET_CHECKOUT_URL_SUCCESS, this.onGetCheckoutUrlSuccess.bind( this ) );
    this.mediator.subscribe( CHECKOUT_WINDOW_FOCUSED, this.onCheckoutWindowFocused.bind( this ) );
};

CheckoutWindow.prototype.onGetCheckoutUrlStarted = function onGetCheckoutUrlStarted() {
    this.open({ url: '' });
};

CheckoutWindow.prototype.onGetCheckoutUrlError = function onGetCheckoutUrlError() {
    this.close();
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
        this.popup = window.open( url || '', '_blank', params );
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