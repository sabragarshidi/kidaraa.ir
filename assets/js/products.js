// Product sliders (Swiper) + homepage product tabs.
(function () {
    document.querySelectorAll('[data-product-slider]').forEach(function (box) {
        if (typeof Swiper === 'undefined') return;
        new Swiper(box.querySelector('.swiper'), {
            slidesPerView: 1.38,
            spaceBetween: 0,
            slidesPerGroup: 1,
            breakpoints: {
                768: { slidesPerView: 3 },
                1200: { slidesPerView: 4 }
            },
            navigation: { prevEl: box.querySelector('.js-prev'), nextEl: box.querySelector('.js-next') }
        });
    });

    document.querySelectorAll('[data-product-tabs]').forEach(function (box) {
        var tabs = box.querySelectorAll('[data-tab]');
        var items = box.querySelectorAll('[data-grid] > [data-cat]');
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var slug = tab.dataset.tab;
                function has(it) { return (' ' + it.dataset.cat + ' ').indexOf(' ' + slug + ' ') > -1; }
                var match = slug === 'all' ? items.length : Array.prototype.filter.call(items, has).length;
                tabs.forEach(function (t) { t.setAttribute('aria-selected', t === tab ? 'true' : 'false'); });
                items.forEach(function (it) {
                    // tabs with no captured products fall back to showing all
                    it.hidden = !(slug === 'all' || !match || has(it));
                });
            });
        });
    });
})();
