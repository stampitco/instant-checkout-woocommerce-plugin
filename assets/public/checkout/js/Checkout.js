import $ from 'jquery';

const Checkout = function Checkout( { $element, settings } ) {
    this.$element = $element;
    this.settings = settings;
    this.init();
}

Checkout.prototype.init = function init() {
    this.bindEvents();
};

Checkout.prototype.bindEvents = function bindEvents() {

};

export default Checkout;