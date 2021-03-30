import $ from 'jquery';

import Options from './Options';
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
    const $checkoutButtons = $( '.stamp-ic-checkout-button' );

    if( $checkoutButtons.length > 0 && window.stampIcCheckout ) {

        const options = new Options( window.stampIcCheckout || {} );
        const mediator = new Mediator( {} );
        const checkoutWindow = new CheckoutWindow( options, mediator );
        const checkoutOverlay = new CheckoutOverlay( options, mediator );
        const api = new Api( options );

        $checkoutButtons.each( function( index, button ) {
            new Checkout( options, $( button ), api, mediator, new CheckoutParams( options, $( button ) ) );
        } );
    }
}
