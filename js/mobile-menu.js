jQuery(document).ready(function($) {
    $('body').addClass('js');

    var $menu = $('.menu-tertiary-menu-container, .nav-secondary, .nav-primary'),
        $menulink = $('.menu-link'),
        $wrap = $('.site-container');

    $menulink.click(function() {
        $menulink.toggleClass('active');
        $wrap.toggleClass('active');
        return false;
    });
});
