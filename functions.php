<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Ora Wellness' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.2.2' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add Accessibility support
add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu',  'search-form', 'skip-links', 'rems' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Don’t need the full CSS header output on the page
add_action( 'stylesheet_uri', 'ora_add_stylesheet', 10, 2 );
function ora_add_stylesheet() {
    return get_stylesheet_directory_uri() . '/orawellness.css';
}

//* Add Typekit fonts
add_action( 'genesis_after', 'ora_add_web_font_loader' );
function ora_add_web_font_loader() {
    echo '<script>
       WebFontConfig = {
          typekit: { id: \'agy8tbj\' }
       };

       (function(d) {
          var wf = d.createElement(\'script\'), s = d.scripts[0];
          wf.src = \'https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js\';
          s.parentNode.insertBefore(wf, s);
       })(document);
    </script>' . "\n";
}

//* Add Brad Frost-inspired mobile menu JS
add_action( 'wp_enqueue_scripts', 'ora_add_mobile_menu_js' );
function ora_add_mobile_menu_js() {
    wp_enqueue_script( 'mobile-menu-js', get_stylesheet_directory_uri() . '/js/navigation.js', array( 'jquery' ) );
    wp_enqueue_script( 'modernizr', get_stylesheet_directory_uri() . '/js/modernizr-flexbox.min.js' );
}

//* Add link for menu on mobile
add_action( 'genesis_site_title', 'ora_add_mobile_menu_link', 8 );
function ora_add_mobile_menu_link() {
    echo '<a class="menu-link"><span>Menu</span></a>';
}

//* Add mobile menu
add_action( 'genesis_before_header', 'ora_add_mobile_menu' );
function ora_add_mobile_menu() {
    echo '<div class="mobile-menu">';
    // main navigation
    wp_nav_menu( array(
        'theme_location' => 'primary',
        'menu_class' => 'menu genesis-nav-menu menu-primary',
        'menu_id' => 'mobile-menu-primary'
    ) );
    // secondary navigation
    wp_nav_menu( array(
        'theme_location' => 'secondary',
        'menu_class' => 'menu genesis-nav-menu menu-secondary',
        'menu_id' => 'mobile-menu-secondary'
    ) );
    // tertiary navigation
    wp_nav_menu( array(
        'theme_location' => 'tertiary-menu',
        'menu_class' => 'menu genesis-nav-menu menu-tertiary',
        'menu_id' => 'mobile-menu-tertiary'
    ) );
    echo '</div>';
}

//* Move primary menu to genesis_header_right
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header_right', 'genesis_do_nav' );

//* Add tertiary menu
add_action( 'init', 'ora_register_tertiary_menu' );
function ora_register_tertiary_menu() {
    register_nav_menu( 'tertiary-menu', __( 'Tertiary Menu' ) );
}

//* Display tertiary menu
add_action( 'genesis_before_header', 'ora_add_tertiary_menu' );
function ora_add_tertiary_menu() {
    wp_nav_menu( array(
        'theme_location' => 'tertiary-menu',
        'menu_class' => 'menu genesis-nav-menu menu-tertiary js-superfish sf-js-enabled sf-arrows wrap'
    ) );
}

//* Add search form to tertiary menu
add_filter( 'wp_nav_menu_items','ora_add_search_box_to_menu', 10, 2 );
function ora_add_search_box_to_menu( $menu, $args ) {
    if( 'tertiary-menu' == $args->theme_location ) {
        $menu .= sprintf( '<li id="search-form" class="menu-item">%s</li>', __( genesis_search_form() ) );
    }
    return $menu;
}

//* Customize search form input box text
add_filter( 'genesis_search_text', 'sp_search_text' );
function sp_search_text( $text ) {
    return 'Search&hellip;';
}

//* Add footer menu
add_action( 'init', 'ora_register_footer_menu' );
function ora_register_footer_menu() {
    register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );
}

//* Customize footer text and display menu
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'ora_custom_footer' );
function ora_custom_footer() { ?>
    <span>&copy; <?php echo date( 'Y' ); ?> OraWellness. All rights reserved.</span>
    <span> <?php wp_nav_menu( array(
        'theme_location' => 'footer-menu',
        'menu_class' => 'menu genesis-nav-menu menu-footer js-superfish sf-js-enabled sf-arrows'
    ) ); ?></span>
    <span class="designer"><a href="http://pautlerdesign.com/" target="_blank">Designed by Pautler Design</a></span>
    <?php
}

//* Add footer widget area 4
add_action( 'widgets_init', 'ora_add_footer_sidebar_4' );
function ora_add_footer_sidebar_4() {
    genesis_register_sidebar( array (
        'id'            => 'footer-4',
        'name'          => 'Footer 4',
        'description'   => __( 'Footer 4 widget area.', 'genesischild' )
    ));
}
add_theme_support( 'genesis-footer-widgets', 4 );

//* Add floating call-to-action bar
add_action( 'widgets_init', 'ora_add_cta_footer' );
function ora_add_cta_footer() {
    genesis_register_sidebar( array (
        'id'            => 'floating-cta',
        'name'          => 'Floating Call-to-Action',
        'description'   => __( 'Call-to-Action bar on homepage.', 'genesischild' )
    ));
}

//* restrict homepage to 6 posts
add_action( 'pre_get_posts', 'ora_limit_homepage_posts' );
function ora_limit_homepage_posts( $query ) {
    if ( $query->is_main_query() && !is_admin() ) {
        $query->set( 'posts_per_page', '6' );
    }
}

//* Customize “read more” text
add_filter( 'excerpt_more', 'ora_read_more_link' );
function ora_read_more_link() {
    return '&hellip;<a class="more-link" href="' . get_permalink() . '">Read More</a>';
}

//* Add custom thumbnail sizes
add_action( 'after_setup_theme', 'ora_custom_image_sizes' );
function ora_custom_image_sizes() {
    add_image_size( 'tagged-content-thumb', 180, 180, true );
    add_image_size( 'targeted-landing-page-banner', 1400, 350, true );
    add_image_size( 'targeted-landing-page-featured', 339, 226, true );
}

//* Add custom pagination function for tagged-content pages
//* Adapted from http://callmenick.com/post/custom-wordpress-loop-with-pagination
function custom_pagination( $numpages = '', $pagerange = '', $paged = '' ) {

    if ( empty( $pagerange ) ) {
        $pagerange = 2;
    }

    /**
    * This first part of our function is a fallback
    * for custom pagination inside a regular loop that
    * uses the global $paged and global $wp_query variables.
    *
    * It's good because we can now override default pagination
    * in our theme, and use this function in default quries
    * and custom queries.
    */
    global $paged;
    if ( empty( $paged ) ) {
        $paged = 1;
    }

    if ( '' == $numpages ) {
        global $wp_query;
        $numpages = $wp_query->max_num_pages;
        if( ! $numpages ) {
            $numpages = 1;
        }
    }

    /**
    * We construct the pagination arguments to enter into our paginate_links
    * function.
    */
    $pagination_args = array(
        'base'                  => get_pagenum_link( 1 ) . '%_%',
        'format'                => 'page/%#%',
        'total'                 => $numpages,
        'current'               => $paged,
        'show_all'              => false,
        'end_size'              => 1,
        'mid_size'              => $pagerange,
        'prev_next'             => True,
        'prev_text'             => __( 'Previous page' ),
        'next_text'             => __( 'Next page' ),
        'type'                  => 'plain',
        'add_args'              => false,
    );

    $paginate_links = paginate_links( $pagination_args );

    if ( $paginate_links ) {
        echo '<nav class="custom-pagination">';
        echo $paginate_links;
        echo '</nav>';
    }
}
