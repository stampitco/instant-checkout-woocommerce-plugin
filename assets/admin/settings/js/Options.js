const Options = function Options( params ) {
    Object.assign( this, params );
}

/**
 * @return {Object}
 */
Options.prototype.getInlineCssEditorSettings = function getInlineCssEditorSettings() {
    return typeof this.inlineCssEditorSettings !== 'undefined' ? this.inlineCssEditorSettings : {}
}

export default Options;