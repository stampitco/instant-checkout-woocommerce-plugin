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

### Button appearance in theme:

The button does not appear in the theme by default because it may brake the html/css 
layout that the theme has.

Additional steps are required in order to display the button. Here is an example of how to show
the button on the product details page in the Storefront theme:

- Define the callbacks

File:
```
/wp-content/themes/storefront/inc/woocommerce/storefront-woocommerce-template-functions.php
```

Code:
```php
if ( ! function_exists( 'storefront_stamp_ic_wc' ) ) {
    /*
     * Display the Instant Checkout button on Product details page 
     */
    function storefront_stamp_ic_wc() {
        if( ! class_exists( 'Stamp_IC_WooCommerce_Plugin' ) ) {
            return;
        }
        if( is_product() ) {
            global $product;
            /*
             * Outputs the button html 
             * Hooked in: [plugin_dir]/includes/checkout/class-checkout-loader.php
             * Callback: \Stamp_IC_WC_Checkout::show_checkout_button
             * Defined in: [plugin_dir]/includes/checkout/class-wc-checkout.php
             */
            do_action( 'stamp_ic_checkout_do_checkout_button', $product->get_id() );
        }
    }
}

if ( ! function_exists( 'storefront_stamp_ic_wc_checkout_button_attributes' ) ) {
    /*
     * Add additional html attributes to the Instant Checkout button 
     */
    function storefront_stamp_ic_wc_checkout_button_attributes( $attributes ) {

        if( empty( $attributes[ 'style' ] ) ) {
            $attributes[ 'style' ] = '';
        }

        if( ! empty( $attributes[ 'class' ] && is_array( $attributes[ 'class' ] ) ) ) {
            $attributes[ 'class' ][] = 'alt';
        }

        $attributes[ 'style' ] .= 'display:block;margin-bottom:15px;';

        return $attributes;
    }
}
```

- Attach the callback to the appropriate hooks:

File:
```
/wp-content/themes/storefront/inc/woocommerce/storefront-woocommerce-template-hooks.php
```

Code:
```php
if ( class_exists( 'Stamp_IC_WooCommerce_Plugin' ) ) {
    add_action( 'woocommerce_before_add_to_cart_button', 'storefront_stamp_ic_wc' );
    add_filter( 'stamp_ic_checkout_button_attributes', 'storefront_stamp_ic_wc_checkout_button_attributes' );
}
```
