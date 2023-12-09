=== Paylode Gateway for WooCommerce ===
Contributors: raphyabak
Tags: paylode, woocommerce, payments, online store, credit card, debit card, bank account, payment gateway, Nigeria, Fintech
Requires at least: 3.1
Tested up to: 6.2.1
Stable tag: 1.0
License: MIT
License URI: https://github.com/raphyabak/paylode-gateway/blob/master/LICENSE

Integrate the Paylode Gateway seamlessly into your WooCommerce online store. Accept payments via credit card, debit card, and bank account directly on your store with the official Paylode Gateway for WooCommerce.

== Description ==

Accept payments via credit card, debit card, and bank account directly on your WooCommerce store with the official Paylode Gateway for WooCommerce.

= Plugin Features =

* Seamless integration with Paylode Gateway.
* Efficient handling of collections, including Card, Account, Mobile money, Bank Transfers, USSD, Barter, and 1voucher.
* Support for recurring payments through Tokenization and Subscriptions.
* Capability to split payments between multiple recipients.

= Requirements =

1. Paylode Gateway for Business [API Keys](https://docs.paylodeservices.com)
2. [WooCommerce](https://woocommerce.com/)
3. Supported PHP version: 7.4.0 - 8.1.0

== Installation ==

= Automatic Installation =
1. Login to your WordPress Dashboard.
2. Click on "Plugins > Add New" from the left menu.
3. In the search box, type __Paylode Woocommerce__.
4. Click on __Install Now__ for __Paylode Woocommerce__ to install the plugin on your site.
5. Confirm the installation.
6. Activate the plugin.
7. Navigate to "WooCommerce > Settings" from the left menu and click the "Checkout" tab.
8. Click on the __Paylode__ link from the available Checkout Options.
9. Configure your __Paylode Woocommerce__ settings accordingly.

= Manual Installation =
1. Download the plugin zip file.
2. Login to your WordPress Admin. Click on "Plugins > Add New" from the left menu.
3. Click on the "Upload" option, then click "Choose File" to select the zip file you downloaded. Click "OK" and "Install Now" to complete the installation.
4. Activate the plugin.
5. Navigate to "WooCommerce > Settings" from the left menu and click the "Checkout" tab.
6. Click on the __Paylode__ link from the available Checkout Options.
7. Configure your __Paylode Woocommerce__ settings accordingly.

For FTP manual installation, [check here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Configure the plugin =
To configure the plugin, go to __WooCommerce > Settings__ from the left menu, click __Checkout__ tab. Click on __Paylode__.

* __Enable/Disable__ - check the box to enable Paylode Woocommerce.
* __API Key__ - Enter your API key obtained from the "API Keys" page on your Paylode Gateway account dashboard.
* __Modal Title__ - (Optional) customize the title of the Pay Modal. Default is Paylode.
* Click __Save Changes__ to save your changes.



= Best Practices =
1. Always check the Paylode Gateway Dashboard to confirm the status of a transaction.
2. Keep your API keys secure and private; do not share them with anyone.
3. Change from the default secret hash on the WordPress admin and apply the same on the Paylode Gateway Dashboard.
4. Ensure you install the most recent version of the Paylode Gateway WordPress plugin.

= Debugging Errors =
If you encounter errors, refer to the [error messages documentation](https://docs.paylodeservices.com). For `authorization` and `validation` error responses, double-check your API keys and request. If you get a `server` error, contact the support team.

= Support =
For additional assistance using this library, contact the developer experience (DX) team via [email](mailto:developers@paylodegateway.com) or on [slack](https://bit.ly/34Vkzcg).

You can also follow us [@RaphWebb_Inc](https://twitter.com/RaphWebb_Inc) and share your feedback.

= Contribution guidelines =
We welcome your contributions. Read more about our community contribution guidelines [here](/CONTRIBUTING.md).

= License =
By contributing to the Paylode Gateway for WooCommerce, you agree that your contributions will be licensed under its [MIT license](/LICENSE).

== Frequently Asked Questions ==

= What Do I Need To Use The Plugin =
1. Open an account on [Paylode Gateway for Business](https://paylodeservices.com)

== Changelog ==
= 1.0 =
* Initial release

== Screenshots ==

== Other Notes ==