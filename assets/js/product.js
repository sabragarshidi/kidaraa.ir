// Single product: gallery (Swiper + zoom + lightbox), WC variation swatches, qty stepper, tabs, live viewers.
(function () {
    var $ = window.jQuery;
    var root = document.querySelector('.kd-product');
    if (!root) return;

    var ICON_MINUS = '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14"/></svg>';
    var ICON_PLUS = '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M12 5v14"/></svg>';

    /* ---------- Gallery ---------- */
    var gal = root.querySelector('[data-gallery]');
    var swiper = null, slides = [], thumbs = [];
    function setActiveThumb(i) {
        thumbs.forEach(function (t, k) { t.classList.toggle('is-active', k === i); });
        if (thumbs[i] && window.matchMedia('(min-width:992px)').matches) {
            var box = thumbs[i].parentNode;
            if (box.scrollHeight > box.clientHeight) box.scrollTop = thumbs[i].offsetTop - box.offsetTop - 20;
        }
    }
    if (gal) {
        slides = [].slice.call(gal.querySelectorAll('.swiper-slide'));
        thumbs = [].slice.call(gal.querySelectorAll('[data-thumb]'));
        if (typeof Swiper !== 'undefined') {
            swiper = new Swiper(gal.querySelector('.kd-main'), {
                slidesPerView: 1, speed: 400, allowTouchMove: slides.length > 1,
                navigation: { prevEl: gal.querySelector('.kd-prev'), nextEl: gal.querySelector('.kd-next') },
                on: { slideChange: function (s) { setActiveThumb(s.activeIndex); } }
            });
        }
        thumbs.forEach(function (t, i) {
            t.addEventListener('click', function () { if (swiper) swiper.slideTo(i); setActiveThumb(i); });
        });

        // inner hover zoom (desktop, pointer devices)
        var fine = window.matchMedia('(hover:hover) and (pointer:fine) and (min-width:992px)');
        gal.querySelectorAll('.kd-zoom').forEach(function (a) {
            var img = a.querySelector('img');
            a.addEventListener('mousemove', function (e) {
                if (!fine.matches || !img) return;
                var r = a.getBoundingClientRect();
                img.style.transformOrigin = ((e.clientX - r.left) / r.width * 100) + '% ' + ((e.clientY - r.top) / r.height * 100) + '%';
                img.style.transform = 'scale(1.8)';
            });
            a.addEventListener('mouseleave', function () { if (img) img.style.transform = ''; });
            a.addEventListener('click', function (e) { e.preventDefault(); openLightbox(slides.indexOf(a.closest('.swiper-slide'))); });
        });
    }

    // lightbox
    var lb = null, lbIndex = 0;
    function lbSrc(i) { var a = slides[i] && slides[i].querySelector('.kd-zoom'); return a ? a.getAttribute('data-full') || a.href : ''; }
    function lbShow(i) {
        lbIndex = (i + slides.length) % slides.length;
        lb.querySelector('img').src = lbSrc(lbIndex);
        if (swiper) swiper.slideTo(lbIndex, 0);
    }
    function lbClose() { if (lb) { lb.remove(); lb = null; document.body.style.overflow = ''; document.removeEventListener('keydown', lbKey); } }
    function lbKey(e) { if (e.key === 'Escape') lbClose(); else if (e.key === 'ArrowRight') lbShow(lbIndex - 1); else if (e.key === 'ArrowLeft') lbShow(lbIndex + 1); }
    function openLightbox(i) {
        if (i < 0 || lb) return;
        lb = document.createElement('div');
        lb.className = 'kd-lb';
        lb.innerHTML = '<button type="button" class="kd-lb-x" aria-label="بستن">&times;</button>' +
            (slides.length > 1 ? '<button type="button" class="kd-lb-p" aria-label="قبلی">&#8250;</button><button type="button" class="kd-lb-n" aria-label="بعدی">&#8249;</button>' : '') +
            '<img alt="">';
        document.body.appendChild(lb);
        document.body.style.overflow = 'hidden';
        lbShow(i);
        lb.addEventListener('click', function (e) {
            if (e.target.closest('.kd-lb-p')) lbShow(lbIndex - 1);
            else if (e.target.closest('.kd-lb-n')) lbShow(lbIndex + 1);
            else if (e.target.tagName !== 'IMG') lbClose();
        });
        document.addEventListener('keydown', lbKey);
    }

    // variation image -> gallery
    function setVariationImage(v) {
        if (!gal || !v || !v.image || !v.image.src) return;
        var idx = -1;
        slides.forEach(function (s, k) {
            var im = s.querySelector('img');
            if (im && (im.getAttribute('data-orig-full') === v.image.full_src || im.getAttribute('data-orig') === v.image.src)) idx = k;
        });
        if (idx < 0) {
            var a = slides[0].querySelector('.kd-zoom'), im = slides[0].querySelector('img');
            im.src = v.image.src; im.classList.remove('kd-img-missing');
            a.setAttribute('data-full', v.image.full_src || v.image.src); a.href = v.image.full_src || v.image.src;
            if (thumbs[0]) thumbs[0].querySelector('img').src = v.image.gallery_thumbnail_src || v.image.src;
            idx = 0;
        }
        if (swiper) swiper.slideTo(idx); setActiveThumb(idx);
    }
    function resetImage() {
        if (!gal || !slides[0]) return;
        var a = slides[0].querySelector('.kd-zoom'), im = slides[0].querySelector('img');
        if (im.getAttribute('data-orig') && im.getAttribute('src') !== im.getAttribute('data-orig')) {
            im.src = im.getAttribute('data-orig');
            a.setAttribute('data-full', im.getAttribute('data-orig-full')); a.href = im.getAttribute('data-orig-full');
            if (thumbs[0]) thumbs[0].querySelector('img').src = im.getAttribute('data-orig');
        }
    }

    /* ---------- Quantity stepper ---------- */
    function enhanceQty(scope) {
        scope.querySelectorAll('.quantity').forEach(function (q) {
            if (q.dataset.kd) return; q.dataset.kd = '1';
            var input = q.querySelector('input.qty'); if (!input || input.type === 'hidden') return;
            var mk = function (cls, html, label) {
                var b = document.createElement('button'); b.type = 'button'; b.className = cls; b.innerHTML = html; b.setAttribute('aria-label', label); return b;
            };
            var minus = mk('kd-qty-btn minus', ICON_MINUS, 'کاهش تعداد'), plus = mk('kd-qty-btn plus', ICON_PLUS, 'افزایش تعداد');
            q.insertBefore(minus, input); input.after(plus);
            function step(d) {
                var min = parseFloat(input.min) || 0, max = parseFloat(input.max), st = parseFloat(input.step) || 1, v = (parseFloat(input.value) || 0) + d * st;
                if (v < min) v = min; if (!isNaN(max) && max > 0 && v > max) v = max;
                input.value = v; input.dispatchEvent(new Event('change', { bubbles: true }));
            }
            minus.addEventListener('click', function () { step(-1); });
            plus.addEventListener('click', function () { step(1); });
        });
    }
    enhanceQty(root);

    /* ---------- Swatches on top of WC's variation selects ---------- */
    var summary = root.querySelector('.kd-summary');
    var colorMap = {};
    try { colorMap = JSON.parse(summary.getAttribute('data-swatches') || '{}'); } catch (e) {}
    var form = root.querySelector('form.variations_form');
    var priceEl = root.querySelector('.kd-price');
    var origPrice = priceEl ? priceEl.innerHTML : '';

    function swatchColor(name) {
        if (colorMap[name]) return colorMap[name];
        var k = Object.keys(colorMap).filter(function (n) { return n.toLowerCase() === name.toLowerCase(); })[0];
        if (k) return colorMap[k];
        return window.CSS && CSS.supports('color', name.toLowerCase().replace(/\s+/g, '')) ? name.toLowerCase().replace(/\s+/g, '') : '';
    }
    function buildSwatches() {
        if (!form) return;
        form.querySelectorAll('table.variations select').forEach(function (sel) {
            var row = sel.closest('tr'), isColor = /colou?r/i.test(sel.name + ' ' + (row.querySelector('label') || {}).textContent);
            var box = document.createElement('div'); box.className = 'kd-swatches' + (isColor ? ' is-color' : ' is-label');
            [].slice.call(sel.options).forEach(function (o) {
                if (!o.value) return;
                var b = document.createElement('button'); b.type = 'button'; b.className = 'kd-sw'; b.setAttribute('data-value', o.value);
                b.setAttribute('aria-label', o.textContent); b.title = o.textContent;
                var c = isColor ? swatchColor(o.textContent.trim()) : '';
                if (c) { b.innerHTML = '<span style="background:' + c + '"></span>'; b.classList.add('is-dot'); }
                else { b.textContent = o.textContent; b.classList.add('is-pill'); }
                b.addEventListener('click', function () {
                    if (b.disabled) return;
                    sel.value = (sel.value === o.value) ? '' : o.value;
                    if ($) $(sel).trigger('change'); else sel.dispatchEvent(new Event('change', { bubbles: true }));
                    sync();
                });
                box.appendChild(b);
            });
            sel.parentNode.insertBefore(box, sel);
            sel.classList.add('kd-hidden-select');
            var lab = row.querySelector('label'); if (lab && !row.querySelector('.kd-selected')) {
                var s = document.createElement('span'); s.className = 'kd-selected'; lab.after(s);
            }
        });
    }
    function sync() {
        if (!form) return;
        form.querySelectorAll('table.variations select').forEach(function (sel) {
            var row = sel.closest('tr'), box = row.querySelector('.kd-swatches'); if (!box) return;
            var opts = {}; [].slice.call(sel.options).forEach(function (o) { opts[o.value] = o; });
            box.querySelectorAll('.kd-sw').forEach(function (b) {
                var o = opts[b.getAttribute('data-value')];
                b.disabled = !o || o.disabled;
                b.classList.toggle('is-active', sel.value === b.getAttribute('data-value'));
                b.setAttribute('aria-pressed', sel.value === b.getAttribute('data-value') ? 'true' : 'false');
            });
            var sn = row.querySelector('.kd-selected');
            if (sn) sn.textContent = sel.value && opts[sel.value] ? opts[sel.value].textContent : '';
        });
    }
    buildSwatches(); sync();
    if (form && $) {
        $(form).on('found_variation', function (e, v) {
            sync(); setVariationImage(v);
            if (priceEl && v && v.price_html) priceEl.innerHTML = v.price_html;
            enhanceQty(form);
        });
        $(form).on('reset_data hide_variation woocommerce_update_variation_values', function (e) {
            sync();
            if (e.type !== 'woocommerce_update_variation_values') {
                if (priceEl) priceEl.innerHTML = origPrice; resetImage();
            }
        });
        $(form).on('click', '.reset_variations', function () { setTimeout(sync, 0); });
    }

    // Buy Now on an unselected variable product: let WC's own "select options" alert fire
    root.querySelectorAll('.kd-buy-now').forEach(function (b) {
        b.addEventListener('click', function (e) {
            var w = b.closest('.woocommerce-variation-add-to-cart');
            if (w && w.classList.contains('woocommerce-variation-add-to-cart-disabled')) {
                e.preventDefault(); var a = w.querySelector('.single_add_to_cart_button'); if (a) a.click();
            }
        });
    });

    /* ---------- Tabs (pills on desktop, accordion on mobile) ---------- */
    var tabsBox = root.querySelector('[data-tabs]');
    function activate(key, toggle) {
        if (!tabsBox) return;
        tabsBox.querySelectorAll('[data-tab]').forEach(function (t) {
            var on = t.getAttribute('data-tab') === key;
            if (toggle && on && t.classList.contains('is-active')) on = false;
            t.classList.toggle('is-active', on);
            var h = t.querySelector('.kd-tab-head'); if (h) h.setAttribute('aria-expanded', on ? 'true' : 'false');
        });
        tabsBox.querySelectorAll('[data-tab-link]').forEach(function (a) {
            a.parentNode.classList.toggle('is-active', a.getAttribute('data-tab-link') === key);
        });
    }
    if (tabsBox) {
        activate(tabsBox.querySelector('[data-tab].is-active') ? tabsBox.querySelector('[data-tab].is-active').getAttribute('data-tab') : '');
        tabsBox.querySelectorAll('[data-tab-link]').forEach(function (a) {
            a.addEventListener('click', function (e) { e.preventDefault(); activate(a.getAttribute('data-tab-link')); });
        });
        tabsBox.querySelectorAll('.kd-tab-head').forEach(function (h) {
            h.addEventListener('click', function () { activate(h.closest('[data-tab]').getAttribute('data-tab'), true); });
        });
        var openReviews = function () {
            if (!tabsBox.querySelector('[data-tab="reviews"]')) return;
            activate('reviews'); tabsBox.scrollIntoView({ behavior: 'smooth', block: 'start' });
        };
        root.querySelectorAll('.woocommerce-review-link').forEach(function (a) {
            a.addEventListener('click', function (e) { e.preventDefault(); openReviews(); });
        });
        if (/^#(reviews|tab-reviews|comment-)/.test(location.hash)) setTimeout(openReviews, 50);
    }

    /* ---------- Live viewers counter (demo: random 30-40, refreshes) ---------- */
    var pv = root.querySelector('.product-count-view');
    if (pv) {
        var min = +pv.dataset.min || 30, max = +pv.dataset.max || 40, out = pv.querySelector('.kd-viewers');
        var rnd = function () { out.textContent = (Math.floor(Math.random() * (max - min + 1)) + min).toLocaleString('fa-IR'); };
        rnd(); setInterval(rnd, +pv.dataset.timeout || 10000);
    }
})();
