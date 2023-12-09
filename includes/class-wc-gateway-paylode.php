<?php

class WC_Gateway_Paylode extends WC_Payment_Gateway
{

    public function __construct()
    {
        // Define the properties, e.g., ID, title, etc.
        $this->id = 'paylode';
        $this->method_title = __('Paylode Gateway', 'paylode-gateway');
        $this->method_description = __('Allows payments using Paylode Gateway.', 'paylode-gateway');

        // Load the settings and form fields
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables
        $this->title = $this->get_option('title');

        // Save settings
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'paylode-gateway'),
                'type' => 'checkbox',
                'label' => __('Enable Paylode Payment', 'paylode-gateway'),
                'default' => 'no',
            ),
            'title' => array(
                'title' => __('Title', 'paylode-gateway'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'paylode-gateway'),
                'default' => __('Paylode Payment', 'paylode-gateway'),
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => __('Description', 'paylode-gateway'),
                'type' => 'textarea',
                'description' => __('Payment method description that the customer will see on your checkout.', 'paylode-gateway'),
                'default' => __('Make a payment using the Paylode gateway.', 'paylode-gateway'),
                'desc_tip' => true,
            ),
            'api_key' => array(
                'title' => __('Public API Key', 'paylode-gateway'),
                'type' => 'text',
                'description' => __('Enter your Paylode API Key. Obtain this from your Paylode account.', 'paylode-gateway'),
                'default' => '',
                'desc_tip' => true,
                'placeholder' => __('API Key', 'paylode-gateway'),
            ),
            'sandbox_mode' => array(
                'title' => __('Sandbox Mode', 'paylode-gateway'),
                'type' => 'checkbox',
                'label' => __('Enable Sandbox Mode', 'paylode-gateway'),
                'description' => __('Place the payment gateway in development mode.', 'paylode-gateway'),
                'default' => 'no',
                'desc_tip' => true,
            ),
        );
    }

    // public function payment_fields()
    // {
    //     // Display the payment form on the checkout page
    // }

    public function process_payment($order_id)
    {
        $order = wc_get_order($order_id);

        // Since the process is asynchronous (due to the iframe), we'll instruct WooCommerce to NOT redirect immediately.
        return array(
            'result' => 'success',
            'redirect' => $order->get_checkout_payment_url(true), // We're not redirecting immediately using PHP, the JavaScript will handle it.
        );
    }

    public function payment_scripts()
    {

        // Load only on checkout page.
        if (!is_checkout_pay_page() && !isset($_GET['key'])) {
            return;
        }

        // if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
        //     return;
        // }

        $expiry_message = sprintf(
            /* translators: %s: shop cart url */
            __('Sorry, your session has expired. <a href="%s" class="wc-backward">Return to shop</a>', 'rave-woocommerce-payment-gateway'),
            esc_url(wc_get_page_permalink('shop'))
        );

        $nonce_value = sanitize_text_field(wp_unslash($_REQUEST['_wpnonce']));

        $order_key = urldecode(sanitize_text_field(wp_unslash($_GET['key'])));
        $order_id = absint(get_query_var('order-pay'));

        $order = wc_get_order($order_id);

        // if ( empty( $nonce_value ) || ! wp_verify_nonce( $nonce_value ) ) {

        //     WC()->session->set( 'refresh_totals', true );
        //     wc_add_notice( __( 'We were unable to process your order, please try again.', 'rave-woocommerce-payment-gateway' ) );
        //     wp_safe_redirect( $order->get_cancel_order_url() );
        //     return;
        // }

        // if ( $this->id !== $order->get_payment_method() ) {
        //     return;
        // }

        wp_enqueue_script('jquery');

        wp_enqueue_script('paylode-gateway', plugins_url('js/paylode-gateway.js', __FILE__), array('jquery'), '1.0', true);
        $payment_args = array();

        // if ( is_checkout_pay_page() && get_query_var( 'order-pay' ) ) {
        $publicKey = $this->get_option('api_key'); // assuming you stored the public key in the settings
        // $amount = $order->get_total(); // get order total
        // $currency = get_woocommerce_currency(); // get currency
        // $email = $order->get_billing_email(); // get billing email
        $firstName = $order->get_billing_first_name();
        $lastName = $order->get_billing_last_name();
        $phoneNumber = $order->get_billing_phone();
        $redirectUrl = $this->get_return_url($order);
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
        // }
        update_post_meta($order_id, '_paylode_payment_txn_ref', $txnref);
        // }
        wp_localize_script('paylode-gateway', 'paylode_payment_args', $payment_args);
    }

}
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}
// Add the gateway to WooCommerce
function add_paylode_gateway($methods)
{
    $methods[] = 'WC_Gateway_Paylode';
    return $methods;
}

// function output_iframe($order_id)
// {
//     $order = wc_get_order($order_id);
//     $iframe_url = $order->get_meta('_iframe_url'); // assuming you saved the iframe URL as order meta.

//     if ($iframe_url) {
//         echo '<iframe src="' . esc_url($iframe_url) . '" width="100%" height="500px"></iframe>';
//     }
// }
// add_action('woocommerce_receipt_{payment_id}', 'output_iframe');

add_filter('woocommerce_payment_gateways', 'add_paylode_gateway');
