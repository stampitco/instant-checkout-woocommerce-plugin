import $ from 'jquery';

import {
    GET_CHECKOUT_URL_ERROR,
    GET_CHECKOUT_URL_STARTED,
    CHECKOUT_WINDOW_CLOSED,
    CHECKOUT_WINDOW_FOCUSED,
} from './events';

const CheckoutOverlay = function CheckoutOverlay( { logo, linkText, overlayText, mediator } ) {
    this.logo = logo;
    this.linkText = linkText;
    this.overlayText = overlayText;
    this.mediator = mediator;
    this.$element = null;
    this.init();
}

CheckoutOverlay.prototype.init = function init() {
    this.mediator.subscribe( GET_CHECKOUT_URL_STARTED, this.onGetCheckoutUrlStarted.bind( this ) );
    this.mediator.subscribe( GET_CHECKOUT_URL_ERROR, this.onGetCheckoutUrlError.bind( this ) );
    this.mediator.subscribe( CHECKOUT_WINDOW_CLOSED, this.onCheckoutWindowClosed.bind( this ) );
    this.bindEvents();
};

CheckoutOverlay.prototype.bindEvents = function bindEvents() {
    if( this.$element ) {
        this.$element.find( '#stamp-ic-wc-overlay-link' ).click( this.onOverlayLinkClick.bind( this ) );
    }
};

CheckoutOverlay.prototype.onGetCheckoutUrlStarted = function onGetCheckoutUrlStarted() {
    this.open();
}

CheckoutOverlay.prototype.onGetCheckoutUrlError = function onGetCheckoutUrlError() {
    this.close();
}

CheckoutOverlay.prototype.onCheckoutWindowClosed = function onCheckoutWindowClosed() {
    this.close();
}

CheckoutOverlay.prototype.open = function open() {
    if( ! this.$element ) {
        this.$element = $( this.getHtml() );
        $( 'body' ).append( this.$element );
        this.bindEvents();
    }
    this.$element.removeClass( 'stamp-ic-wc-overlay-hidden' );
    this.$element.addClass( 'stamp-ic-wc-overlay-active' );
};

CheckoutOverlay.prototype.onOverlayLinkClick = function onOverlayLinkClick( event ) {
    event.preventDefault();
    this.mediator.publish( CHECKOUT_WINDOW_FOCUSED );
};

CheckoutOverlay.prototype.isOpened = function isOpened() {
    return this.$element && this.$element.hasClass( 'stamp-ic-wc-overlay-active' );
};

CheckoutOverlay.prototype.close = function close() {
    if( this.$element ) {
        this.$element.removeClass( 'stamp-ic-wc-overlay-active' );
        this.$element.addClass( 'stamp-ic-wc-overlay-hidden' );
    }
};

CheckoutOverlay.prototype.remove = function remove() {
    if( this.$element ) {
        this.$element.remove();
    }
};

CheckoutOverlay.prototype.getHtml = function getHtml() {
    return `
        <div id="stamp-ic-wc-overlay" class="stamp-ic-wc-overlay-hidden">
            <div id="stamp-ic-wc-overlay-modal">
                <img src="${this.logo}" alt="${this.linkText}" id="stamp-ic-wc-overlay-logo">
                <p id="stamp-ic-wc-overlay-text">${this.overlayText}</p>
                <a href="#" id="stamp-ic-wc-overlay-link">${this.linkText}</a>
            </div>
        </div>
    `
};

export default CheckoutOverlay;