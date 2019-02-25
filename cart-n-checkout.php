<?php
/*
Plugin Name: Cart'n'Checkout
Plugin URI: https://bogaczek.com
Description: This plugin generates <code>[woocommerce_cart]</code> and <code>[woocommerce_checkout]</code> on Checkout page, redirects empty cart to Shop page, alters „has been added to your cart” message, and redirects to cart'n'checkout page after product added to cart. No configuration required. Don't forget to delete Cart page and set Woccomerce Cart to checkout and Wocommerce Checkout to checkout.
Version: 0.8
Author: Black Sun
Author URI: https://bogaczek.com
Text Domain: cart-n-checkout
*/
defined('ABSPATH') or die();

/**
 * @sources    https://businessbloomer.com/?p=80321, https://businessbloomer.com/?p=494, http://jaworowi.cz/woocommerce-przekierowanie-z-pustego-koszyka-na-strone-sklepu-11639.php
 * @authors        Rodolfo Melogli, Jakub Jaworowicz, Dexter
 */

//show cart on checkout page
function dexter_cart_on_checkout_page_only() {
	if ( is_wc_endpoint_url( 'order-received' ) ) return;
 	echo do_shortcode('[woocommerce_cart]');
}
add_action( 'woocommerce_before_checkout_form', 'dexter_cart_on_checkout_page_only', 5 );

//redirect empty cart to shop page
function dexter_redirect_empty_cart_to_shop_page() {
	if ( class_exists( 'WooCommerce' ) ) {
		$shop_url = get_permalink(woocommerce_get_page_id('shop'));
		if (( is_cart() || is_checkout()) && 0 == WC()->cart->get_cart_contents_count() && ! is_wc_endpoint_url( 'order-received' ) && ! is_wc_endpoint_url( 'order-pay' ) ) {
 			wp_safe_redirect( $shop_url );
 			exit;
		}
	}
}
add_action( 'template_redirect', 'dexter_redirect_empty_cart_to_shop_page' );

//edit "has been added to your cart" message
function dexter_custom_add_to_cart_message() {
	$message = __( 'I siup produkt w koszyku! :)' );
	return $message;
}
add_filter( 'wc_add_to_cart_message_html', 'dexter_custom_add_to_cart_message' );

//redirect to cart after product add
function dexter_redirect_checkout_add_cart( $url ) {
    $url = get_permalink( get_option( 'woocommerce_cart_page_id' ) ); 
    return $url;
}
 
add_filter( 'woocommerce_add_to_cart_redirect', 'dexter_redirect_checkout_add_cart' );
?>