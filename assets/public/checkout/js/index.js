import $ from 'jquery';

import Checkout from './Checkout';
import CheckoutWindow from './CheckoutWindow';
import CheckoutOverlay from './CheckoutOverlay';
import CheckoutParams from './CheckoutParams';
import Api from './Api';
import Mediator from './Mediator';

$(function() {
    $('body').on('updated_wc_div', initApp );
    initApp();
});

function initApp() {
    const $checkoutButton = $( '#stamp-ic-checkout-button' );

    if( $checkoutButton.length > 0 && window.stampIcCheckout ) {

        const api = new Api( window.stampIcCheckout.api || {} );
        const mediator = new Mediator( {} );

        const checkoutWindow = new CheckoutWindow( { mediator } );
        const checkoutOverlay = new CheckoutOverlay( { ...window.stampIcCheckout.overlay || {}, mediator } );
        const checkoutParams = new CheckoutParams({
            $button: $checkoutButton,
            page: window.stampIcCheckout.page,
        })

        const checkout = new Checkout( {
            $button: $checkoutButton,
            api,
            mediator,
            checkoutParams,
            debug: parseInt( window.stampIcCheckout.debug ) !== 0,
        } );
    }
}
