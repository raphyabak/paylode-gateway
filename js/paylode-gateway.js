
function loadScript(src, callback) {
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = src;
  script.onload = callback;
  document.body.appendChild(script);
}

// URL of the PaylodeCheckout script
var paylodeCheckoutScriptUrl = 'https://checkout.paylodeservices.com/checkout.js';
loadScript(paylodeCheckoutScriptUrl, function () {
  jQuery(document).ready(function ($) {
    // Check if paylode_payment_args are available
    console.log(paylode_payment_args);
    if (typeof paylode_payment_args !== 'undefined' && paylode_payment_args !== null) {
      // Define your data for PaylodeCheckout using paylode_payment_args
      let data = {
        publicKey: paylode_payment_args.public_key,
        amount: paylode_payment_args.amount,
        email: paylode_payment_args.email,
        firstname: paylode_payment_args.first_name,
        lastname: paylode_payment_args.last_name,
        redirectUrl: paylode_payment_args.redirect_url,
        phonenumber: paylode_payment_args.phone_number,
        currency: paylode_payment_args.currency,
        // onClose: function () {
        //   console.log('Iframe closed');
        // },
        onClose: function (response) {
          jQuery.ajax({
            url: paylode_payment_args.ajax_url,
            method: 'POST',
            data: {
              action: 'handle_paylode_success',
              order_id: paylode_payment_args.order_id,
              response: response
            },
            success: function (res) {
              if (res.success) {
                // Redirect to thank you page or handle any other success actions here
                window.location.href = paylode_payment_args.redirect_url;
              } else {
                alert('Error processing order. Please contact support.');
              }
            }
          });
        },
        onSuccess: function (response) {
          jQuery.ajax({
            url: paylode_payment_args.ajax_url,
            method: 'POST',
            data: {
              action: 'handle_paylode_success',
              order_id: paylode_payment_args.order_id,
              response: response
            },
            success: function (res) {
              if (res.success) {
                // Redirect to thank you page or handle any other success actions here
                window.location.href = paylode_payment_args.redirect_url;
              } else {
                alert('Error processing order. Please contact support.');
              }
            }
          });
          alert('This is success')
        },
      };
      PaylodeCheckout.setup(data).openIframe();
    }
  });
});


// jQuery(document).ready(function() {
//   if(paylode_params) { // check if params are set
//       PaylodeCheckout.setup(paylode_params).openIframe(); // Call your functions
//   }
// });

// jQuery(document).ready(function ($) {
//   $('body').on('click', '#place_order', function (event) {
//     // Check if Paylode payment method is selected
//     if ($('input[name="payment_method"]:checked').val() === 'paylode') {
//       // event.preventDefault(); // Prevent the default action
//       console.log(window.paylode_payment_args);
//       // return
//       if (window.paylode_payment_args) {
//         // Define your data for PaylodeCheckout
//         let data = {
//           // Here you would populate the necessary data
//           publicKey: window.paylode_payment_args.public_key, // Make sure you fetch this securely
//           amount: window.paylode_payment_args.amount,
//           email: window.paylode_payment_args.email,
//           firstname: window.paylode_payment_args.first_name,
//           lastname: window.paylode_payment_args.last_name,
//           redirectUrl: window.paylode_payment_args.redirect_url,
//           phonenumber: window.paylode_payment_args.phone_number,
//           currency: window.paylode_payment_args.currency,


//           onClose: function () {
//             console.log('Iframe closed');
//           },
//           onSuccess: function () {
//             console.log('Payment succeeded');
//           }
//         };
//         PaylodeCheckout.setup(data).openIframe();
//       }
//     }
//   });
// });

