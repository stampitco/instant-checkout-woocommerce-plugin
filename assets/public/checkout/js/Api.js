import $ from 'jquery';

const Api = function Api( params ) {

    const {
        ajaxUrl,
        nonce,
        nonceName,
        getCheckoutUrlAction,
    } = params;

    this.ajaxUrl = ajaxUrl;
    this.nonce = nonce;
    this.nonceName = nonceName;
    this.getCheckoutUrlAction = getCheckoutUrlAction;
}

Api.prototype.getCheckoutUrl = function getCheckoutUrl( data ) {

    data[ `${this.nonceName}` ] = this.nonce;
    data[ 'action' ] = this.getCheckoutUrlAction;

    return new Promise(( resolve, reject ) => {
        $.ajax({
            type: 'POST',
            url: this.ajaxUrl,
            data,
            success: function onGetCheckoutUrlSuccess( { data: { message, checkout_url } } ) {
                resolve({
                    message,
                    checkout_url,
                });
            },
            error: function onGetCheckoutUrlError( jqXHR ) {
                reject({
                    status: jqXHR.status,
                    message: jqXHR.responseJSON.data.message,
                });
            }
        });
    });

};

export default Api;