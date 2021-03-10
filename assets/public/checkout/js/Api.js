import $ from 'jquery';

/**
 * Api constructor
 *
 * @param {Options} options
 */
const Api = function Api( options ) {
    this.options = options;
}

Api.prototype.getCheckoutUrl = function getCheckoutUrl( data ) {
    data[ `${this.options.getAjaxNonceName()}` ] = this.options.getAjaxNonce();
    data[ 'action' ] = this.options.getAjaxCheckoutUrlAction();
    return new Promise(( resolve, reject ) => {
        $.ajax({
            type: 'POST',
            url: this.options.getAjaxUrl(),
            data,
            success: function onGetCheckoutUrlSuccess( { data: { message, checkout_url } } ) {
                resolve({
                    message,
                    checkout_url,
                });
            },
            error: function onGetCheckoutUrlError( jqXHR ) {

                const reason = {
                    status: jqXHR.status,
                    message: 'Failed to get the Instant Checkout url'
                };

                if( jqXHR.responseJSON.hasOwnProperty('data') && Array.isArray( jqXHR.responseJSON.data ) && jqXHR.responseJSON.data[0].hasOwnProperty('message') ) {
                    reason.message = jqXHR.responseJSON.data[0].message;
                }

                reject(reason);
            }
        });
    });
};

Api.prototype.clearCart = function clearCart( data ) {
    data[ `${this.options.getAjaxNonceName()}` ] = this.options.getAjaxNonce();
    data[ 'action' ] = this.options.getAjaxClearCartAction();
    return new Promise(( resolve, reject ) => {
        $.ajax({
            type: 'POST',
            url: this.options.getAjaxUrl(),
            data,
            success: function onClearCartSuccess( { data: { message } } ) {
                resolve({
                    message,
                });
            },
            error: function onClearCartError( jqXHR ) {

                const reason = {
                    status: jqXHR.status,
                    message: 'Failed to clear the cart'
                };

                if( jqXHR.responseJSON.hasOwnProperty('data') && Array.isArray( jqXHR.responseJSON.data ) && jqXHR.responseJSON.data[0].hasOwnProperty('message') ) {
                    reason.message = jqXHR.responseJSON.data[0].message;
                }

                reject(reason);
            }
        });
    });
}

export default Api;