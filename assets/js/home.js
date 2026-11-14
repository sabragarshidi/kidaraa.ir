// Homepage sections: hero fade slider, outfits slider, brands strip, video lightbox, lookbook popups.
(function () {
    'use strict';

    function ready(fn) {
        if (document.readyState !== 'loading') fn();
        else document.addEventListener('DOMContentLoaded', fn);
    }

    ready(function () {
        // Hero (Revolution Slider replacement): fade, 9s autoplay.
        document.querySelectorAll('[data-hero-slider]').forEach(function (el) {
            var slides = el.querySelectorAll('.swiper-slide').length;
            new Swiper(el.querySelector('.swiper'), {
                effect: 'fade',
                fadeEffect: { crossFade: true },
                speed: 1000,
                loop: slides > 2,
                autoplay: { delay: 9000, disableOnInteraction: false },
                allowTouchMove: slides > 1,
            });
        });

        // Outfits: small cards, 2.3 / 3 / 4 per view.
        document.querySelectorAll('[data-outfits-slider]').forEach(function (el) {
            new Swiper(el.querySelector('.swiper'), {
                slidesPerView: 4,
                spaceBetween: 20,
                loop: true,
                grabCursor: true,
                navigation: { prevEl: el.querySelector('.js-outfits-prev'), nextEl: el.querySelector('.js-outfits-next') },
                breakpoints: { 0: { slidesPerView: 2.3, spaceBetween: 12 }, 768: { slidesPerView: 3, spaceBetween: 16 }, 1200: { slidesPerView: 4, spaceBetween: 20 } },
            });
        });

        // Brands strip: 2 / 3 / 4 / 5 per view.
        document.querySelectorAll('[data-brands-slider]').forEach(function (el) {
            new Swiper(el.querySelector('.swiper'), {
                slidesPerView: 2,
                spaceBetween: 0,
                loop: false,
                grabCursor: true,
                breakpoints: { 576: { slidesPerView: 3 }, 992: { slidesPerView: 4 }, 1200: { slidesPerView: 5 } },
            });
        });

        // Video lightbox (YouTube iframe injected on open, removed on close).
        document.querySelectorAll('[data-video-open]').forEach(function (btn) {
            var modal = document.querySelector(btn.getAttribute('data-video-open'));
            if (!modal) return;
            var box = modal.querySelector('[data-video-frame]');
            function open() {
                var src = btn.getAttribute('data-video-src');
                src += (src.indexOf('?') > -1 ? '&' : '?') + 'autoplay=1';
                box.innerHTML = '<iframe class="absolute inset-0 h-full w-full" src="' + src + '" title="Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.documentElement.style.overflow = 'hidden';
            }
            function close() {
                box.innerHTML = '';
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.documentElement.style.overflow = '';
            }
            btn.addEventListener('click', open);
            modal.querySelectorAll('[data-video-close]').forEach(function (c) { c.addEventListener('click', close); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.classList.contains('hidden')) close(); });
        });

        // Lookbook hotspots: hover (desktop) / tap (touch) opens the product popup.
        document.querySelectorAll('[data-lookbook]').forEach(function (root) {
            var spots = root.querySelectorAll('[data-spot]');
            var timers = new WeakMap();
            function setOpen(s, on) {
                clearTimeout(timers.get(s));
                if (on) s.setAttribute('data-open', ''); else s.removeAttribute('data-open');
            }
            spots.forEach(function (s) {
                s.addEventListener('mouseenter', function () {
                    spots.forEach(function (o) { if (o !== s) setOpen(o, false); });
                    setOpen(s, true);
                });
                s.addEventListener('mouseleave', function () {
                    timers.set(s, setTimeout(function () { setOpen(s, false); }, 150));
                });
                s.addEventListener('click', function (e) {
                    if (e.target.closest('a')) return;
                    var willOpen = !s.hasAttribute('data-open');
                    spots.forEach(function (o) { setOpen(o, false); });
                    setOpen(s, willOpen);
                });
                var close = s.querySelector('[data-spot-close]');
                if (close) close.addEventListener('click', function (e) { e.stopPropagation(); setOpen(s, false); });
            });
            document.addEventListener('click', function (e) {
                if (!root.contains(e.target)) spots.forEach(function (o) { setOpen(o, false); });
            });
        });
    });
})();
