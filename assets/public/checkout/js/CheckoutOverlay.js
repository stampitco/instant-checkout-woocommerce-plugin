import $ from 'jquery';

const CheckoutOverlay = function CheckoutOverlay( { logo, linkText, overlayText } ) {
    this.logo = logo;
    this.linkText = linkText;
    this.overlayText = overlayText;
    this.$element = null;
}

CheckoutOverlay.prototype.open = function open() {
    if( ! this.$element ) {
        this.$element = $( this.getHtml() );
        $( 'body' ).append( this.$element );
        this.bindEvents();
    }
    this.$element.addClass( 'stamp-ic-wc-overlay-active' )
};

CheckoutOverlay.prototype.bindEvents = function bindEvents() {
    if( this.$element ) {
        this.$element.find( '#stamp-ic-wc-overlay-link' ).click( this.onOverlayLinkClick.bind( this ) );
    }
};

CheckoutOverlay.prototype.onOverlayLinkClick = function onOverlayLinkClick( event ) {
    event.preventDefault();
    this.remove();
};

CheckoutOverlay.prototype.isOpened = function isOpened() {
    return this.$element && this.$element.hasClass( 'stamp-ic-wc-overlay-active' );
};

CheckoutOverlay.prototype.close = function close() {
    if( this.$element ) {
        this.$element.removeClass( 'stamp-ic-wc-overlay-active' )
    }
};

CheckoutOverlay.prototype.remove = function remove() {
    if( this.$element ) {
        this.$element.remove();
    }
};

CheckoutOverlay.prototype.getHtml = function getHtml() {
    return `
        <div id="stamp-ic-wc-overlay">
            <div id="stamp-ic-wc-overlay-modal">
                <img src="${this.logo}" alt="${this.linkText}" id="stamp-ic-wc-overlay-logo">
                <p id="stamp-ic-wc-overlay-text">${this.overlayText}</p>
                <a href="#" id="stamp-ic-wc-overlay-link">${this.linkText}</a>
            </div>
        </div>
    `
};

export default CheckoutOverlay;