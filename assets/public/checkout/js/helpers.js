
const isButtonFromMiniCart = function isButtonFromMiniCart( $button ) {
    return $button.parents( '.widget_shopping_cart_content' ).length > 0
}

export {
    isButtonFromMiniCart,
}