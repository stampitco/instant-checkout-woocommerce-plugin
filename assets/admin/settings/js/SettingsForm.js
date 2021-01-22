import $ from 'jquery';

const SettingsForm = function SettingsForm( { $element } ) {
    this.$element = $element;
    this.init();
}

SettingsForm.prototype.init = function init() {
    this.$element.parsley();
    this.bindEvents();
};

SettingsForm.prototype.bindEvents = function bindEvents() {

};

export default SettingsForm;