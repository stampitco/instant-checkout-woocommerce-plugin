# Instant Checkout for WooCommerce

### Plugin install:

After you activate the plugin you need to configure the settings that the plugin requires:

You can access them from WP Admin:

```
/wp-admin/options-general.php?page=stamp-ic-wc
```

or use the WP CLI command like this:
```
wp stamp-ic-wc settings --stamp_api_key=[STAMP_API_KEY]
```

### Button appearance in a theme:

The button appears in two pages:

- In the product details page - the `woocommerce_after_add_to_cart_button` action is used by default to output the button html
- In the cart page - the `woocommerce_proceed_to_checkout` action is used by default to output the button html

Here is an example of how to show the button in a custom position on the product details page:

Enable button custom positioning by using the `stamp_ic_checkout_button_custom_position` filter
```php
if ( ! function_exists( 'stamp_ic_wc_button_custom_position_enabled' ) ) {
    function stamp_ic_wc_button_custom_position_enabled( $is_enabled ) {
        $is_enabled = true;
        return $is_enabled;
    }
    add_filter( 'stamp_ic_checkout_button_custom_position', 'stamp_ic_wc_button_custom_position_enabled' );
}
```

Attach the button html to another action:
```php
if ( ! function_exists( 'stamp_ic_wc_do_display_ic_button' ) ) {
    function stamp_ic_wc_do_display_ic_button() {
        if( class_exists( 'Stamp_IC_WooCommerce_Plugin' ) && is_product() ) {
            do_action( 'stamp_ic_checkout_do_checkout_button' );
        }
    }
    add_action( 'woocommerce_before_add_to_cart_button', 'stamp_ic_wc_do_display_ic_button' );
}
```

You can add custom html attributes to the button using:
```php
if ( ! function_exists( 'stamp_ic_wc_checkout_button_custom_attributes' ) ) {
    function stamp_ic_wc_checkout_button_custom_attributes( $attributes ) {

        if( empty( $attributes[ 'style' ] ) ) {
            $attributes[ 'style' ] = '';
        }

        if( ! empty( $attributes[ 'class' ] && is_array( $attributes[ 'class' ] ) ) ) {
            $attributes[ 'class' ][] = 'alt';
        }

        $attributes[ 'style' ] .= 'display:block;margin-bottom:15px;';

        return $attributes;
    }
    add_filter( 'stamp_ic_checkout_button_attributes', 'stamp_ic_wc_checkout_button_custom_attributes' );
}
```