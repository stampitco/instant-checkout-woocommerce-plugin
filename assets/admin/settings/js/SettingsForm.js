import $ from 'jquery';

const SettingsForm = function SettingsForm( { $element, options } ) {
    /**
     * @type {jQuery} $element
     */
    this.$element = $element;
    /**
     * @type {Options} options
     */
    this.options = options;

    this.init();
}

SettingsForm.prototype.init = function init() {
    this.$element.parsley();
    this.bindEvents();
};

SettingsForm.prototype.bindEvents = function bindEvents() {

};

export default SettingsForm;