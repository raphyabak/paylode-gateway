<?php
/**
 * Plugin Name: Paylode Gateway for WooCommerce
 * Plugin URI: https://paylodeservices.com
 * Description: Integrates Paylode Gateway with WooCommerce.
 * Version: 1.0
 * Author: Raphael Abayomi
 * Author URI: https://github.com/raphyabak
 * Text Domain: paylode-gateway
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function enqueue_paylode_scripts()
{
    if (is_checkout_pay_page() && get_query_var('order-pay')) {
        wp_enqueue_script('paylode-gateway', plugins_url('js/paylode-gateway.js', __FILE__), array('jquery'), '1.0', true);

        $gateways = WC_Payment_Gateways::instance();
        $paylode_gateway = $gateways->payment_gateways()['paylode']; // Replace 'paylode_gateway' with the actual ID of your payment gateway if different.

        $order_id = absint(get_query_var('order-pay'));

        $order = wc_get_order($order_id);

        if ($order->get_status() == 'failed' || $order->get_status() == 'cancelled') {
            // header('Location: ' . wc_get_cart_url());
            // die();
            wp_safe_redirect($order->get_cancel_order_url());
            return;
        }

        $payment_args = array();

        $publicKey = $paylode_gateway->get_option('api_key');
        // $publicKey = $this->get_option('api_key'); // assuming you stored the public key in the settings

        $firstName = $order->get_billing_first_name();
        $lastName = $order->get_billing_last_name();
        $phoneNumber = $order->get_billing_phone();
        $redirectUrl = $paylode_gateway->get_return_url($order);
        $email = $order->get_billing_email();
        $amount = $order->get_total();
        $txnref = 'WOOC_' . $order_id . '_' . time();
        $the_order_id = $order->get_id();
        $the_order_key = $order->get_order_key();
        $currency = $order->get_currency();
        $custom_nonce = wp_create_nonce();
        // $redirect_url  = WC()->api_request_url( 'FLW_WC_Payment_Gateway' ) . '?order_id=' . $order_id . '&_wpnonce=' . $custom_nonce;

        // if ( $the_order_id === $order_id && $the_order_key === $order_key ) {

        $payment_args['email'] = $email;
        $payment_args['amount'] = $amount;
        $payment_args['tx_ref'] = $txnref;
        $payment_args['currency'] = $currency;
        $payment_args['public_key'] = $publicKey;
        $payment_args['redirect_url'] = $redirectUrl;
        // $payment_args['payment_options'] = $this->payment_options;
        $payment_args['phone_number'] = $order->get_billing_phone();
        $payment_args['first_name'] = $order->get_billing_first_name();
        $payment_args['last_name'] = $order->get_billing_last_name();
        $payment_args['consumer_id'] = $order->get_customer_id();
        $payment_args['ip_address'] = $order->get_customer_ip_address();
        // $payment_args['title']           = esc_html__( 'Order Payment', 'rave-woocommerce-payment-gateway' );
        // $payment_args['description']     = 'Payment for Order: ' . $order_id;
        // $payment_args['logo']            = wp_get_attachment_url( get_theme_mod( 'custom_logo' ) );
        $payment_args['checkout_url'] = wc_get_checkout_url();
        $payment_args['cancel_url'] = $order->get_cancel_order_url();
        $payment_args['ajax_url'] = admin_url('admin-ajax.php');
        $payment_args['order_id'] = $order->get_id();
        // }
        update_post_meta($order_id, '_paylode_payment_txn_ref', $txnref);
        // }
        wp_localize_script('paylode-gateway', 'paylode_payment_args', $payment_args);

    }
}

function handle_paylode_success_callback()
{
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $response = isset($_POST['response']) ? $_POST['response'] : null;

    $order = wc_get_order($order_id);
    if (!$order) {
        wp_send_json_error('Invalid order.');
        return;
    }

    // TODO: Verify the $response data with Paylode's API.

    // If payment is valid and verified, mark the order as completed.
    $order->payment_complete();

    // Clear the cart
    WC()->cart->empty_cart();

    wp_send_json_success('Payment processed successfully.');
    $redirect_url = WC_Payment_Gateways::instance()->payment_gateways()['paylode']->get_return_url($order);
    header('Location: ' . $redirect_url);
    die();
}

add_action('wp_enqueue_scripts', 'enqueue_paylode_scripts');
add_action('wp_ajax_handle_paylode_success', 'handle_paylode_success_callback');
add_action('wp_ajax_nopriv_handle_paylode_success', 'handle_paylode_success_callback');
// Check if WooCommerce is active
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    // Include and initialize within the action to ensure WooCommerce classes are loaded
    function initialize_paylode_gateway()
    {
        include_once 'includes/class-wc-gateway-paylode.php';
    }
    add_action('plugins_loaded', 'initialize_paylode_gateway');
}
