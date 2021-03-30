import $ from 'jquery';

const StylingForm = function StylingForm( { $element } ) {
    this.$element = $element;
    this.init();
}

StylingForm.prototype.init = function init() {
    this.$element.parsley();
    this.bindEvents();
};

StylingForm.prototype.bindEvents = function bindEvents() {
    this.$element.find('input[name="stamp_ic_button_color"]').change(this.onButtonColorChange.bind(this));
};

StylingForm.prototype.onButtonColorChange = function onButtonColorChange( event ) {

    const $container = this.$element.find('#stamp-ic-button-custom-color-container');
    const $input = $container.find('input[name="stamp_ic_button_custom_color"]');

    if( $(event.target).val() === 'custom' ) {
        $container.removeClass('hidden');
        $input.attr({ required: true });
    } else {
        $container.addClass('hidden');
        $input.attr({ required: false });
    }
};

export default StylingForm;