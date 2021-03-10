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
    const $checkoutButton = $( '#stamp-ic-checkout-button' );
    if( $checkoutButton.length > 0 && window.stampIcCheckout ) {
        const options = new Options( window.stampIcCheckout || {} );
        const mediator = new Mediator( {} );
        new CheckoutWindow( options, mediator );
        new CheckoutOverlay( options, mediator );
        new Checkout( options, $checkoutButton, new Api( options ), mediator, new CheckoutParams( options, $checkoutButton ) );
    }
}
