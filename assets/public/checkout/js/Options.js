/**
 * Options constructor
 *
 * @param {Object} params
 */
const Options = function Options( params ) {
    Object.assign( this, params );
}

/**
 * @return {(string|null)} The ajax url
 */
Options.prototype.getAjaxUrl = function getAjaxUrl() {
    return typeof this.api.ajaxUrl !== 'undefined' ? this.api.ajaxUrl : null
}

/**
 * @return {(string|null)} The ajax nonce value
 */
Options.prototype.getAjaxNonce = function getAjaxNonce() {
    return typeof this.api.nonce !== 'undefined' ? this.api.nonce : null
}

/**
 * @return {(string|null)} The ajax nonce name
 */
Options.prototype.getAjaxNonceName = function getAjaxNonceName() {
    return typeof this.api.nonceName !== 'undefined' ? this.api.nonceName : null
}

/**
 * @return {(string|null)} The ajax clear cart action name
 */
Options.prototype.getAjaxClearCartAction = function getAjaxClearCartAction() {
    return typeof this.api.clearCartAction !== 'undefined' ? this.api.clearCartAction : null
}

/**
 * @return {(string|null)} The ajax get checkout url action name
 */
Options.prototype.getAjaxCheckoutUrlAction = function getAjaxCheckoutUrlAction() {
    return typeof this.api.getCheckoutUrlAction !== 'undefined' ? this.api.getCheckoutUrlAction : null
}

/**
 * @return {boolean} Is debugging enabled
 */
Options.prototype.isDebugEnabled = function isDebugEnabled() {
    return typeof this.debug !== 'undefined' ? !this.debug : false
}

/**
 * @return {boolean} Are we on the product details page
 */
Options.prototype.isProductPage = function isProductPage() {
    return typeof this.page.isProduct !== 'undefined' ? this.page.isProduct : false
}

/**
 * @return {boolean} Are we on the cart page
 */
Options.prototype.isCartPage = function isCartPage() {
    return typeof this.page.isCart !== 'undefined' ? this.page.isCart : false
}

/**
 * @return {(string|null)} The overlay logo url
 */
Options.prototype.getOverlayLogo = function getOverlayLogo() {
    return typeof this.overlay.logo !== 'undefined' ? this.overlay.logo : null
}

/**
 * @return {(string|null)} The overlay text
 */
Options.prototype.getOverlayText = function getOverlayText() {
    return typeof this.overlay.overlayText !== 'undefined' ? this.overlay.overlayText : null
}

/**
 * @return {(string|null)} The overlay text
 */
Options.prototype.getOverlayLinkText = function getOverlayLinkText() {
    return typeof this.overlay.linkText !== 'undefined' ? this.overlay.linkText : null
}

/**
 * @return {string}
 */
Options.prototype.getPopUpTempUrl = function getPopUpTempUrl() {
    return typeof this.popUpTempUrl !== 'undefined' ? this.popUpTempUrl : ''
}

/**
 * @return {string}
 */
Options.prototype.getPopUpUrl = function getPopUpUrl() {
    return typeof this.popUpUrl !== 'undefined' ? this.popUpUrl : ''
}

/**
 * @return {string}
 */
Options.prototype.getInstantButtonText = function getInstantButtonText() {
    return typeof this.instantCheckoutButtonText !== 'undefined' ? this.instantCheckoutButtonText : 'Instant Checkout'
}

/**
 * @return {string}
 */
Options.prototype.getOrderDoneText = function getOrderDoneText() {
    return typeof this.orderDoneText !== 'undefined' ? this.orderDoneText : 'Your order was placed'
}

export default Options;