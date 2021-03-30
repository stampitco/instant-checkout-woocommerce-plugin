import $ from 'jquery';

import './validators';
import SettingsForm from './SettingsForm';
import StylingForm from "./StylingForm";

$(function() {
    const $settingsForm = $( '#stamp-ic-wc-settings-form' );
    if( $settingsForm.length > 0 ) {
        new SettingsForm( {
            $element: $settingsForm
        } );
    }
    const $stylingForm = $( '#stamp-ic-wc-styling-form' );
    if( $stylingForm.length > 0 ) {
        new StylingForm( {
            $element: $stylingForm
        } );
    }
});
