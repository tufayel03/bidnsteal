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

    if (toggleButton && mobileMenu) {
        toggleButton.addEventListener('click', function () {
            // Toggle the 'active' class on the mobile menu to control visibility.
            mobileMenu.classList.toggle('active');
        });
    }
});