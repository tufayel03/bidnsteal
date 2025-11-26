/**
 * Theme JavaScript for BidnSteal.
 *
 * Handles the mobile navigation toggle and can be extended to add interactive behaviour such as
 * opening search overlays or animating elements.  jQuery is available by default in WordPress, but
 * native JavaScript is used here for lightweight interactions.
 */
document.addEventListener('DOMContentLoaded', function () {
    var toggleButton = document.getElementById('bs-toggle');
    var mobileMenu   = document.getElementById('bs-mobile-menu');
    var navBar       = document.querySelector('.bs-nav');
    var searchOverlay = document.getElementById('bs-search-overlay');
    var searchInput   = document.getElementById('bs-search-input');
    var searchResults = document.getElementById('bs-search-results');
    var searchTriggers = document.querySelectorAll('.bs-search-trigger');
    var searchClose    = document.querySelector('.bs-search-close');
    var searchDebounce;

    if (toggleButton && mobileMenu) {
        toggleButton.addEventListener('click', function () {
            // Toggle the 'active' class on the mobile menu to control visibility.
            mobileMenu.classList.toggle('active');
        });

        mobileMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                mobileMenu.classList.remove('active');
            });
        });
    }

    if (navBar) {
        var handleSticky = function () {
            if (window.scrollY > 10) {
                navBar.classList.add('is-sticky');
            } else {
                navBar.classList.remove('is-sticky');
            }
        };

        handleSticky();
        window.addEventListener('scroll', handleSticky);
    }

    var openSearch = function () {
        if (!searchOverlay) return;
        searchOverlay.classList.add('active');
        searchOverlay.setAttribute('aria-hidden', 'false');
        document.body.classList.add('bs-no-scroll');
        if (searchInput) {
            searchInput.focus();
        }
    };

    var closeSearch = function () {
        if (!searchOverlay) return;
        searchOverlay.classList.remove('active');
        searchOverlay.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('bs-no-scroll');
    };

    if (searchTriggers && searchTriggers.length) {
        searchTriggers.forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                if (!btn.hasAttribute('href') || btn.getAttribute('href') === '#') {
                    e.preventDefault();
                    openSearch();
                }
            });
        });
    }

    if (searchOverlay) {
        searchOverlay.addEventListener('click', function (event) {
            if (event.target === searchOverlay) {
                closeSearch();
            }
        });
    }

    if (searchClose) {
        searchClose.addEventListener('click', closeSearch);
    }

    document.addEventListener('keyup', function (event) {
        if (event.key === 'Escape') {
            closeSearch();
        }
    });

    var renderResults = function (items) {
        if (!searchResults) return;
        if (!items.length) {
            searchResults.innerHTML = '<p class="bs-search-empty">No products found.</p>';
            return;
        }

        var markup = items.map(function (item) {
            return '<a class="bs-search-item" href="' + item.url + '">' +
                '<span class="bs-search-thumb"><img src="' + item.image + '" alt="" /></span>' +
                '<span class="bs-search-meta"><strong>' + item.title + '</strong><span class="bs-search-price">' + (item.price || '') + '</span></span>' +
                '</a>';
        }).join('');

        searchResults.innerHTML = markup;
    };

    var fetchResults = function (term) {
        if (!window.bsThemeData || !bsThemeData.ajax_url) return;
        var url = bsThemeData.ajax_url + '?action=bs_search_products&nonce=' + bsThemeData.nonce + '&term=' + encodeURIComponent(term);

        fetch(url)
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data && data.success && data.data && data.data.items) {
                    renderResults(data.data.items);
                }
            })
            .catch(function () {
                if (searchResults) {
                    searchResults.innerHTML = '<p class="bs-search-empty">Unable to search right now.</p>';
                }
            });
    };

    if (searchInput) {
        searchInput.addEventListener('input', function (event) {
            var term = event.target.value.trim();
            clearTimeout(searchDebounce);

            if (!term) {
                if (searchResults) {
                    searchResults.innerHTML = '';
                }
                return;
            }

            searchDebounce = setTimeout(function () {
                fetchResults(term);
            }, 200);
        });
    }
});
