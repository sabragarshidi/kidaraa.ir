// Cart: the visible "update cart" button proxies the real one inside the cart form.
document.addEventListener('click', function (e) {
  var p = e.target.closest('[data-kd-update-cart]');
  if (!p) return;
  var real = document.querySelector('.woocommerce-cart-form [name="update_cart"]');
  if (!real) return;
  real.disabled = false;
  real.removeAttribute('aria-disabled');
  real.click();
});

// Keep the header cart badge in sync after the cart is updated/changed by AJAX.
if (window.jQuery) {
  jQuery(document.body).on('updated_cart_totals removed_from_cart', function () {
    jQuery(document.body).trigger('wc_fragment_refresh');
  });
}
