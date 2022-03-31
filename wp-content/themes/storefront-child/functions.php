<?php


function mytheme_add_woocommerce_support() {
add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );


add_filter( 'woocommerce_billing_fields', 'my_optional_fields' );
function my_optional_fields( $address_fields ) {
$address_fields['billing_phone']['required'] = false;
return $address_fields;
}
