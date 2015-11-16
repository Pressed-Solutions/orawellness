jQuery(document).ready(function($) {
    $('body').addClass('js');

    // add open/close functionality to mobile toggle
    var $menu = $('.menu-tertiary-menu-container, .nav-secondary, .nav-primary'),
        $menulink = $('.menu-link'),
        $wrap = $('.site-container');

    $menulink.click(function() {
        $menulink.toggleClass('active');
        $wrap.toggleClass('active');
        return false;
    });

    // add "more" toggle to secondary nav
    $( '.site-container > .nav-secondary .sub-menu' ).append( '<li class="menu-item more"><a>More</a></li>' );

    // add open/close functionality to secondary more toggle
    $( '.more a' ).on( 'click', function() {
        $( this ).parents( '.sub-menu' ).toggleClass( 'open' );
    });
});
