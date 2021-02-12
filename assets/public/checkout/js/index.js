import $ from 'jquery';

import Checkout from './Checkout';
import CheckoutWindow from './CheckoutWindow';
import CheckoutOverlay from './CheckoutOverlay';
import Api from './Api';

$(function() {

    const $checkoutButton = $( '.stamp-ic-checkout-button' );

    if( $checkoutButton.length > 0 && window.stampIcCheckout ) {

        const api = new Api( window.stampIcCheckout );
        const checkoutWindow = new CheckoutWindow( { checkoutWindow: null } );

        const checkoutOverlay = new CheckoutOverlay( window.stampIcCheckout.overlay );

        checkoutOverlay.open();

        $checkoutButton.each( function initCheckout() {
            new Checkout( {
                $element: $(this),
                api,
                checkoutWindow,
                debug: parseInt( window.stampIcCheckout.debug ) !== 0,
            } );
        } );
    }
});
