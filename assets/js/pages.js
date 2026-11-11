// Inner pages: FAQ accordion, About testimonial strip.
(function () {
    function ready(fn) { document.readyState !== 'loading' ? fn() : document.addEventListener('DOMContentLoaded', fn); }
    ready(function () {
        document.querySelectorAll('.js-faq-item').forEach(function (item) {
            var btn = item.querySelector('.js-faq-q'), panel = item.querySelector('.js-faq-a');
            if (!btn || !panel) return;
            btn.addEventListener('click', function () {
                var open = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', open ? 'false' : 'true');
                item.classList.toggle('is-closed', open);
                panel.hidden = open;
            });
        });
        document.querySelectorAll('[data-about-testimonials]').forEach(function (el) {
            if (typeof Swiper === 'undefined') return;
            new Swiper(el.querySelector('.swiper'), {
                loop: true, autoplay: { delay: 5000, disableOnInteraction: false },
                pagination: { el: el.querySelector('.js-testimonials-dots'), clickable: true, bulletClass: 'kd-dot', bulletActiveClass: 'is-active' },
            });
        });
    });
})();
