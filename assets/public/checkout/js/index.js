import $ from 'jquery';

import Checkout from './Checkout';
import CheckoutWindow from './CheckoutWindow';
import CheckoutOverlay from './CheckoutOverlay';
import Api from './Api';
import Mediator from './Mediator';

$(function() {

    const $checkoutButton = $( '.stamp-ic-checkout-button' );

    if( $checkoutButton.length > 0 && window.stampIcCheckout ) {

        const api = new Api( window.stampIcCheckout.api || {} );
        const mediator = new Mediator( {} );

        const checkoutWindow = new CheckoutWindow( { mediator } );
        const checkoutOverlay = new CheckoutOverlay( { ...window.stampIcCheckout.overlay || {}, mediator } );

        $checkoutButton.each( function initCheckout() {
            new Checkout( {
                $button: $(this),
                api,
                mediator,
                debug: parseInt( window.stampIcCheckout.debug ) !== 0,
            } );
        } );
    }
});
