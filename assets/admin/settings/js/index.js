import $ from 'jquery';

import './validators';
import SettingsForm from './SettingsForm';

$(function() {
    const $settings_form = $( '#stamp-ic-wc-settings-form' );
    if( $settings_form.length > 0 ) {
        new SettingsForm( {
            $element: $settings_form
        } );
    }
});
