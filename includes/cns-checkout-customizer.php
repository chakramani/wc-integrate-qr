<?php

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'cns_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function cns_override_checkout_fields( $fields ) {
unset($fields['order']['order_comments']);
unset($fields['billing']['billing_company']);
// unset($fields['billing']['billing_address_1']);
// unset($fields['billing']['billing_address_2']);
// unset($fields['billing']['billing_city']);
unset($fields['billing']['billing_postcode']);
unset($fields['billing']['billing_country']);
unset($fields['billing']['billing_state']);

return $fields;
}

add_filter('woocommerce_enable_order_notes_field', '__return_false');

// cart and checkout inline styles
add_action( 'wp_head', 'cns_inline_styles', 900 );
function cns_inline_styles(){
    if ( is_checkout() || is_cart() ){
        ?><style>
        .product-item-thumbnail { float:left; padding-right:10px;}
        .product-item-thumbnail img { margin: 0 !important;}
        dt.variation-Description { display: none;}
        </style><?php
    }
}

// Product thumbnail in checkout
add_filter( 'woocommerce_cart_item_name', 'cns_product_thumbnail_in_checkout', 20, 3 );
function cns_product_thumbnail_in_checkout( $product_name, $cart_item, $cart_item_key ){
    if ( is_checkout() )
    {
        $thumbnail   = $cart_item['data']->get_image(array( 80, 80));
        $image_html  = '<div class="product-item-thumbnail">'.$thumbnail.'</div> ';

        echo $image_html;
    }
    return $product_name;
}

// Cart item qquantity in checkout
add_filter( 'woocommerce_checkout_cart_item_quantity', 'cns_filter_checkout_cart_item_quantity', 20, 3 );
function cns_filter_checkout_cart_item_quantity( $quantity_html, $cart_item, $cart_item_key ){
    return ' <strong class="product-quantity">' . sprintf( '&times; %s', $cart_item['quantity'] ) . '</strong><br clear="all">';
}

//placeholder
add_filter( 'woocommerce_checkout_fields' , 'cns_override_billing_checkout_fields', 20, 1 );
function cns_override_billing_checkout_fields( $fields ) {
    $fields['billing']['billing_first_name']['priority'] = 1;
    $fields['billing']['billing_last_name']['priority'] = 2;
    $fields['billing']['billing_phone']['priority'] = 3;
    $fields['billing']['billing_email']['priority'] = 4;
    $fields['billing']['billing_first_name']['placeholder'] = 'First name *';
    $fields['billing']['billing_last_name']['placeholder'] = 'Last name *';
    $fields['billing']['billing_email']['placeholder'] = 'Email *';
    $fields['billing']['billing_phone']['placeholder'] = 'Phone *';
    $fields['billing']['billing_city']['placeholder'] = 'City *';
    $fields['billing']['billing_address_1']['placeholder'] = 'House number and street name *';
    return $fields;
}

//disable label
add_filter('woocommerce_checkout_fields','cns_wc_checkout_fields_no_label');

// Our hooked in function - $fields is passed via the filter!
// Action: remove label from $fields
function cns_wc_checkout_fields_no_label($fields) {
    // loop by category
    foreach ($fields as $category => $value) {
        // loop by fields
        foreach ($value as $field => $property) {
            // remove label property
            unset($fields[$category][$field]['label']);
        }
    }
     return $fields;
}

//Add title to payment section
add_action( 'woocommerce_review_order_before_payment', 'cns_wc_privacy_message_below_checkout_button' );
 
function cns_wc_privacy_message_below_checkout_button() {
   echo '<h3 class="payment-choices">Payment</h3><h4>Select a payment method:</h4>';
}

// Display the product thumbnail in order received page
add_filter( 'woocommerce_order_item_name', 'cns_order_received_item_thumbnail_image', 10, 3 );
function cns_order_received_item_thumbnail_image( $item_name, $item, $is_visible ) {
    // Targeting order received page only
    if( ! is_wc_endpoint_url('order-received') ) return $item_name;

    // Get the WC_Product object (from order item)
    $product = $item->get_product();

    if( $product->get_image_id() > 0 ){
        $product_image = '<span style="float:left;display:block;width:56px;">' . $product->get_image(array(48, 48)) . '</span>';
        echo $product_image;
    }

    return $item_name;
}
add_action( 'init', 'cns_remove_breadcrumbs' );
 
function cns_remove_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}