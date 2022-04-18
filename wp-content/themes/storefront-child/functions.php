<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */

function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}

/**
 * Custom text on the receipt page.
function isa_order_received_text( $text, $order ) {
    $first_name = $order->get_billing_first_name();
    $new = $text . ' Se le ha enviado un recibo por correo electrónico.';
    return "Gracias, $first_name, $new;
}
add_filter('woocommerce_thankyou_order_received_text', 'isa_order_received_text', 10, 2 );

/**
 * Custom text on the receipt page.
 */
function isa_order_received_text( $text, $order ) {
 
    $first_name = ucfirst($order->get_billing_first_name());
    $last_name = ucfirst($order->get_billing_last_name());
 
    return "Gracias, $first_name $last_name, Se le ha enviado un recibo por correo electrónico!";
}
add_filter('woocommerce_thankyou_order_received_text', 'isa_order_received_text', 10, 2 );
 
/**
 * Add "Print receipt" link to Order Received page and View Order page
 */
function isa_woo_thankyou() {
    echo '<a href="javascript:window.print()" id="wc-print-button">Print receipt</a>';
}
add_action( 'woocommerce_thankyou', 'isa_woo_thankyou', 1);
add_action( 'woocommerce_view_order', 'isa_woo_thankyou', 8 );


function storefront_credit() {
    ?>
    <div class="site-info">
        © Plantas Patraldo, 2021-2022<br/> design by ¡Pinche Poutine! Digital
<a href="https://pinchepoutine.digital">¡Pinche Poutine! Digital</a>
    </div><!-- .site-info -->
    <?php
}
