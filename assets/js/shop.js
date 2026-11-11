(function () {
  var root = document.querySelector('[data-shop]') || document.querySelector('[data-kd-shop]');
  if (!root) return;

  // drawer
  var setOpen = function (v) { root.classList.toggle('kd-shop-open', v); document.body.style.overflow = v ? 'hidden' : ''; };
  root.addEventListener('click', function (e) {
    if (e.target.closest('[data-shop-open]')) setOpen(true);
    else if (e.target.closest('[data-shop-close]') || e.target.closest('[data-shop-overlay]')) setOpen(false);
  });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { setOpen(false); sort && sort.classList.remove('is-open'); } });

  // sort dropdown
  var sort = root.querySelector('[data-sort]');
  if (sort) {
    sort.querySelector('[data-sort-toggle]').addEventListener('click', function (e) {
      e.stopPropagation();
      var o = sort.classList.toggle('is-open');
      e.currentTarget.setAttribute('aria-expanded', o);
    });
    document.addEventListener('click', function (e) { if (!sort.contains(e.target)) sort.classList.remove('is-open'); });
  }

  // grid columns
  var grid = root.querySelector('[data-shop-grid]');
  var views = root.querySelector('[data-views]');
  if (grid && views) {
    var saved = null;
    try { saved = localStorage.getItem('kd_shop_cols'); } catch (e) {}
    var apply = function (v) {
      grid.setAttribute('data-cols', v);
      views.querySelectorAll('[data-view]').forEach(function (b) { b.classList.toggle('is-active', b.getAttribute('data-view') === v); });
    };
    if (saved && views.querySelector('[data-view="' + saved + '"]')) apply(saved);
    views.addEventListener('click', function (e) {
      var b = e.target.closest('[data-view]'); if (!b) return;
      var v = b.getAttribute('data-view'); apply(v);
      try { localStorage.setItem('kd_shop_cols', v); } catch (err) {}
    });
  }

  // dual price range
  var f = root.querySelector('[data-price]');
  if (f) {
    var lo = f.querySelector('[data-lo]'), hi = f.querySelector('[data-hi]'), fill = f.querySelector('[data-fill]');
    var lo2 = f.querySelector('[data-lo-out]'), hi2 = f.querySelector('[data-hi-out]');
    var mn = +f.dataset.min, mx = +f.dataset.max;
    var upd = function (src) {
      var a = +lo.value, b = +hi.value;
      if (a > b) { if (src === lo) { a = b; lo.value = a; } else { b = a; hi.value = b; } }
      var span = (mx - mn) || 1;
      fill.style.left = ((a - mn) / span * 100) + '%';
      fill.style.right = (100 - (b - mn) / span * 100) + '%';
      lo2.textContent = a.toFixed(2); hi2.textContent = b.toFixed(2);
    };
    lo.addEventListener('input', function () { upd(lo); });
    hi.addEventListener('input', function () { upd(hi); });
    upd();
  }
})();
