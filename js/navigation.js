jQuery(document).ready(function($) {
    $( 'body' ).addClass( 'js' );

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
    $( '.more a' ).on( 'click, mouseenter, mouseleave', function() {
        $( this ).parents( '.sub-menu' ).toggleClass( 'open' );
    });
    $( '.more a' ).on( 'mouseover', function() {
        $( this ).parents( '.sub-menu' ).addClass( 'open' );
    });
    $( '.more a' ).on( 'mouseout', function() {
        $( this ).parents( '.sub-menu' ).removeClass( 'open' );
    });

    // move secondary-menu overflow items to a separate dropdown
    jQuery( '#menu-secondary-menu .menu-item.more' ).append( '<ul class="dropdown"></ul>' );
    jQuery( '#menu-secondary-menu .sub-menu li' ).each( function() {
        // get info about this element
        var $this = jQuery( this );
        var $parent = $this.parent();
        var elPosition = $this.position();
        var elWidth = $this.width();
        var elHeight = $this.height();

        // get info about the parent element
        var parentPosition = $parent.position();
        var parentWidth = $parent.width();
        var parentHeight = $parent.height();
        var moreDropdown = $parent.find( '.more .dropdown' );

        // if top of this is greater than or equal to the parent’s height, it needs to be moved
        if ( elPosition.top >= parentHeight ) {
            $this.detach().appendTo( moreDropdown );
        }
    });
});
