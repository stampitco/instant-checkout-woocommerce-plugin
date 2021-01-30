import $ from 'jquery';

import Checkout from './Checkout';
import Api from './Api';

$(function() {

    const $checkoutButton = $( '.stamp-ic-checkout-button' );

    if( $checkoutButton.length > 0 && window.stampIcCheckout ) {

        const api = new Api( {

        } );

        $checkoutButton.forEach( function initCheckout( $element ) {
            new Checkout( {
                $element,
                settings: window.stampIcCheckout,
                api,
            } );
        } );
    }
});
