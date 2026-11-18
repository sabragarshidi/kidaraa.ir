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
        var ct = root.querySelector('.kd-count'); if (ct) ct.textContent = (i + 1).toLocaleString('fa-IR') + ' / ' + slides.length.toLocaleString('fa-IR');
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
            if (splitMap[sel.name]) { buildSplit(sel, splitMap[sel.name]); return; }
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
    /* Fused «design + size» options (Tamineto) → two rows of buttons driving the one hidden select. */
    var splitMap = {};
    try { splitMap = JSON.parse(summary.getAttribute('data-split') || '{}') || {}; } catch (e) {}
    var faNum = function (n) { return String(n).replace(/\d/g, function (d) { return '۰۱۲۳۴۵۶۷۸۹'[d]; }); };
    function buildSplit(sel, map) {
        var row = sel.closest('tr'), td = sel.parentNode;
        var inStock = null;   // value → bool, from WC's variation data when present
        try {
            var vars = JSON.parse(form.getAttribute('data-product_variations') || 'false');
            if (vars) { inStock = {}; vars.forEach(function (v) { var val = v.attributes[sel.name]; if (val) inStock[val] = inStock[val] || !!v.is_in_stock; }); }
        } catch (e) {}
        var designs = [], sizes = [], combo = {};
        Object.keys(map).forEach(function (val) {
            var d = map[val][0], z = map[val][1], k = d + '\u0001' + z;
            if (designs.indexOf(d) < 0) designs.push(d);
            if (sizes.indexOf(z) < 0) sizes.push(z);
            // duplicates like «3 - پاپیون» and «پاپیون - 3»: prefer the one in stock
            if (!combo[k] || (inStock && inStock[val] && !inStock[combo[k]])) combo[k] = val;
        });
        sizes.sort(function (a, b) { return a - b; });
        var ok = function (val) { return !inStock || !!inStock[val]; };
        var state = { d: '', z: '' };
        var cur = map[sel.value]; if (cur) { state.d = cur[0]; state.z = cur[1]; }

        var th = row.querySelector('th, td.label'); if (th) th.style.display = 'none';
        var wrap = document.createElement('div'); wrap.className = 'kd-split';
        function group(title, items, kind, text) {
            var g = document.createElement('div'); g.className = 'kd-split-group';
            g.innerHTML = '<div class="kd-split-label">' + title + ': <span class="kd-selected"></span></div>';
            var box = document.createElement('div'); box.className = 'kd-swatches is-label';
            items.forEach(function (it) {
                var b = document.createElement('button'); b.type = 'button'; b.className = 'kd-sw is-pill';
                b.setAttribute('data-' + kind, it); b.textContent = text(it); b.title = text(it);
                b.addEventListener('click', function () {
                    if (b.disabled) return;
                    state[kind] = state[kind] === it ? '' : it;
                    apply();
                });
                box.appendChild(b);
            });
            g.appendChild(box); wrap.appendChild(g); return g;
        }
        var gd = group('طرح', designs, 'd', function (d) { return d; });
        var gz = group('سایز', sizes, 'z', function (z) { return 'سایز ' + faNum(z); });
        td.insertBefore(wrap, sel);
        sel.classList.add('kd-hidden-select');

        function refresh() {
            gd.querySelectorAll('.kd-sw').forEach(function (b) {
                var d = b.getAttribute('data-d');
                var any = sizes.some(function (z) { var v = combo[d + '\u0001' + z]; return v && ok(v); });
                b.disabled = !any; b.classList.toggle('is-active', state.d === d);
            });
            gz.querySelectorAll('.kd-sw').forEach(function (b) {
                var z = b.getAttribute('data-z');
                var avail = state.d ? (function (v) { return v && ok(v); })(combo[state.d + '\u0001' + z])
                                    : designs.some(function (d) { var v = combo[d + '\u0001' + z]; return v && ok(v); });
                b.disabled = !avail; b.classList.toggle('is-active', state.z === z);
            });
            gd.querySelector('.kd-selected').textContent = state.d;
            gz.querySelector('.kd-selected').textContent = state.z ? faNum(state.z) : '';
        }
        function apply() {
            var v = state.d && state.z ? combo[state.d + '\u0001' + state.z] : '';
            if (state.d && state.z && (!v || !ok(v))) { state.z = ''; v = ''; }
            if (sel.value !== (v || '')) {
                sel.value = v || '';
                if ($) $(sel).trigger('change'); else sel.dispatchEvent(new Event('change', { bubbles: true }));
            }
            refresh();
        }
        if ($) $(form).on('reset_data', function () { if (!sel.value) { state.d = ''; state.z = ''; refresh(); } });
        refresh();
    }

    function sync() {
        if (!form) return;
        form.querySelectorAll('table.variations select').forEach(function (sel) {
            if (splitMap[sel.name]) return;
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

    /* ---------- Below-the-fold cards: scroll-spy, description toggle, mini buy ---------- */
    var tabsBox = root.querySelector('[data-tabs]');
    if (tabsBox) {
        var links = [].slice.call(tabsBox.querySelectorAll('[data-tab-link]'));
        var cards = [].slice.call(tabsBox.querySelectorAll('[data-tab]'));
        var setLink = function (k) { links.forEach(function (a) { a.classList.toggle('is-active', a.getAttribute('data-tab-link') === k); }); };
        if (links[0]) setLink(links[0].getAttribute('data-tab-link'));
        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (es) {
                es.forEach(function (e) { if (e.isIntersecting) setLink(e.target.getAttribute('data-tab')); });
            }, { rootMargin: '-25% 0px -60% 0px' });
            cards.forEach(function (c) { io.observe(c); });
        }
        var d = tabsBox.querySelector('[data-desc]'), tg = tabsBox.querySelector('[data-desc-toggle]');
        if (d && tg) {
            if (d.scrollHeight <= d.clientHeight + 8) { d.classList.add('is-short'); }
            else {
                tg.classList.remove('hidden');
                tg.addEventListener('click', function () {
                    var on = d.classList.toggle('is-open');
                    tg.textContent = on ? 'نمایش کمتر' : 'نمایش کامل توضیحات';
                });
            }
        }
        root.querySelectorAll('.woocommerce-review-link').forEach(function (a) {
            a.addEventListener('click', function (e) {
                var t = document.getElementById('tab-reviews'); if (!t) return;
                e.preventDefault(); t.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    }
    root.querySelectorAll('[data-mini-buy]').forEach(function (b) {
        b.addEventListener('click', function () {
            var main = root.querySelector('.kd-summary .single_add_to_cart_button');
            if (main && !main.disabled && !main.classList.contains('disabled')) { main.click(); return; }
            var sm = root.querySelector('.kd-summary'); if (sm) sm.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
    root.querySelectorAll('[data-to-top]').forEach(function (a) {
        a.addEventListener('click', function (e) { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); });
    });

    /* ---------- Live viewers counter (demo: random 30-40, refreshes) ---------- */
    var pv = root.querySelector('.product-count-view');
    if (pv) {
        var min = +pv.dataset.min || 30, max = +pv.dataset.max || 40, out = pv.querySelector('.kd-viewers');
        var rnd = function () { out.textContent = (Math.floor(Math.random() * (max - min + 1)) + min).toLocaleString('fa-IR'); };
        rnd(); setInterval(rnd, +pv.dataset.timeout || 10000);
    }
})();

// Size guide dialog (inc/size-guide.php)
(function () {
    document.addEventListener('click', function (e) {
        var open = e.target.closest('[data-size-guide-open]');
        if (open) { var d = document.getElementById(open.getAttribute('data-size-guide-open')); if (d && d.showModal) d.showModal(); return; }
        if (e.target.closest('[data-size-guide-close]')) { e.target.closest('dialog').close(); return; }
        if (e.target.tagName === 'DIALOG' && e.target.classList.contains('kd-size-guide')) e.target.close(); // backdrop click
    });
})();
