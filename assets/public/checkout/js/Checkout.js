import $ from 'jquery';

const Checkout = function Checkout( { $element, settings, api } ) {

    this.$element = $element;
    this.settings = settings;
    this.api = api;

    this.onCheckoutButtonClick = this.onCheckoutButtonClick.bind( this );

    this.init();
}

Checkout.prototype.init = function init() {
    this.bindEvents();
};

Checkout.prototype.bindEvents = function bindEvents() {
    this.$element.click( this.onCheckoutButtonClick );
};

Checkout.prototype.onCheckoutButtonClick = function onCheckoutButtonClick( event ) {
    event.preventDefault();
    this.$element.attr( { disabled: true } );
};

export default Checkout;