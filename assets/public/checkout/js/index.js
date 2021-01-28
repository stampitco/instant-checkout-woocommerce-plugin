import $ from 'jquery';

import Checkout from './Checkout';

$(function() {
    const $checkoutButton = $( '.stamp-ic-checkout-button' );
    if( $checkoutButton.length > 0 && window.stampIcCheckout ) {
        $checkoutButton.forEach( function initCheckout( $element ) {
            new Checkout( {
                $element,
                settings: window.stampIcCheckout
            } );
        } );
    }
});
