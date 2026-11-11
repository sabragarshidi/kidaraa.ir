// Kidara WC: +/- quantity stepper on cart/product quantity inputs, auto-enable "Update cart".
(function () {
  function enhance(root) {
    root.querySelectorAll('.quantity:not([data-kd])').forEach(function (q) {
      var input = q.querySelector('input.qty');
      if (!input || input.type === 'hidden') return;
      q.setAttribute('data-kd', '1');
      function mk(sign, label) {
        var b = document.createElement('button');
        b.type = 'button'; b.className = 'kd-qty-btn'; b.textContent = sign; b.setAttribute('aria-label', label);
        b.addEventListener('click', function () {
          var step = parseFloat(input.step) || 1, min = parseFloat(input.min) || 0, max = parseFloat(input.max);
          var v = (parseFloat(input.value) || 0) + (sign === '+' ? step : -step);
          if (v < min) v = min; if (!isNaN(max) && max > 0 && v > max) v = max;
          input.value = v;
          input.dispatchEvent(new Event('change', { bubbles: true }));
        });
        return b;
      }
      q.insertBefore(mk('−', 'Decrease'), input);
      q.appendChild(mk('+', 'Increase'));
    });
  }
  function init() {
    enhance(document);
    document.body.addEventListener('updated_cart_totals', function () { enhance(document); });
    document.addEventListener('change', function (e) {
      if (e.target.matches && e.target.matches('.woocommerce-cart-form input.qty')) {
        var u = document.querySelector('.woocommerce-cart-form [name=update_cart]');
        if (u) u.disabled = false;
      }
    });
  }
  document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', init) : init();
})();
