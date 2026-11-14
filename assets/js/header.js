// Kidara header: sticky state, off-canvas menu, search overlay
(function () {
  var header = document.getElementById('bwp-header');
  var off = document.getElementById('kd-offcanvas');
  var search = document.getElementById('kd-search');
  var cats = document.getElementById('kd-cats');

  function onScroll() {
    if (header) header.classList.toggle('is-sticky', window.scrollY > 100);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  function toggle(el, open, lock) {
    if (!el) return;
    el.classList.toggle('is-open', open);
    el.setAttribute('aria-hidden', open ? 'false' : 'true');
    document.documentElement.style.overflow = open ? 'hidden' : '';
    if (open && lock) {
      var i = el.querySelector('[data-kd-search-input]');
      if (i) setTimeout(function () { i.focus(); }, 50);
    }
  }

  document.addEventListener('click', function (e) {
    var t = e.target;
    if (!t.closest) return;
    if (t.closest('[data-kd-menu-open]')) { toggle(off, true); return; }
    if (t.closest('[data-kd-menu-close]')) { toggle(off, false); return; }
    if (t.closest('[data-kd-cats-open]')) { toggle(cats, !cats.classList.contains('is-open')); document.documentElement.style.overflow = ''; return; }
    if (t.closest('[data-kd-cats-close]') || (cats && cats.classList.contains('is-open') && t.closest('#kd-cats a'))) { toggle(cats, false); document.documentElement.style.overflow = ''; if (!t.closest('a')) return; }
    if (t.closest('[data-kd-search-open]')) { toggle(search, true, true); return; }
    if (t.closest('[data-kd-search-close]')) { toggle(search, false); }
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { toggle(off, false); toggle(search, false); toggle(cats, false); }
  });
})();
