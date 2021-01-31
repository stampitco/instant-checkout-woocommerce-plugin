import $ from 'jquery';

import {
    CHECKOUT_WINDOW_OPENED,
    CHECKOUT_WINDOW_CLOSED,
    CHECKOUT_WINDOW_FOCUSED,
} from './events';

const CheckoutWindow = function CheckoutWindow( { checkoutWindow, $invoker } ) {
    this.checkoutWindow = checkoutWindow;
    this.$invoker = $invoker;
    this.monitorInterval = null;
}

CheckoutWindow.prototype.open = function open( { url, params, title, $invoker } ) {
    if( ! this.checkoutWindow ) {

        this.checkoutWindow = window.open( url, title, params  );

        this.$invoker = $invoker;

        $( document ).trigger( CHECKOUT_WINDOW_OPENED, [ this.$invoker ] );

        this.monitorInterval = window.setInterval( this.monitor.bind(this), 1000 );
    }
};

CheckoutWindow.prototype.monitor = function monitor() {
    if( this.monitorInterval ) {
        try {
            if ( this.checkoutWindow == null || this.checkoutWindow.closed ) {
                window.clearInterval( this.monitorInterval );
                this.close();
            }
        }
        catch ( error ) {
            console.error( error );
        }
    }
};

CheckoutWindow.prototype.close = function close() {
    if( this.checkoutWindow ) {
        this.checkoutWindow.close();
        $( document ).trigger( CHECKOUT_WINDOW_CLOSED, [ this.$invoker ] );
        this.checkoutWindow = null;
        this.$invoker = null;
    }
};

export default CheckoutWindow;