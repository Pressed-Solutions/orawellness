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

//* Add JS
add_action( 'wp_enqueue_scripts', 'ora_theme_JS' );
function ora_theme_JS() {
    // webfont loader
    wp_enqueue_script( 'webfonts', get_stylesheet_directory_uri() . '/js/webfonts.min.js', array(), filemtime( get_stylesheet_directory() . '/js/webfonts.min.js' ) );

    // Add Brad Frost-inspired mobile menu JS
    wp_enqueue_script( 'mobile-menu-js', get_stylesheet_directory_uri() . '/js/navigation.min.js', array( 'jquery' ), filemtime( get_stylesheet_directory() . '/js/navigation.min.js' ) );
    wp_enqueue_script( 'modernizr', get_stylesheet_directory_uri() . '/js/modernizr-flexbox.min.js', array(), filemtime( get_stylesheet_directory() . '/js/modernizr-flexbox.min.js' ) );
}

//* Add Hotjar script
add_action( 'wp_head', 'ora_print_hotjar_scripts' );
function ora_print_hotjar_scripts() {
    echo "<!-- Hotjar Tracking Code for https://www.orawellness.com -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:294151,hjsv:5};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
</script>
";
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

//* Add search form, cart, and logout link to tertiary menu
add_filter( 'wp_nav_menu_items','ora_add_search_box_to_menu', 10, 2 );
function ora_add_search_box_to_menu( $menu, $args ) {
    if( 'tertiary-menu' == $args->theme_location ) {
        // if user is logged in, show logout link
        if ( is_user_logged_in() ) {
            $menu .= sprintf(
                '<li class="menu-item" id="logout"><a href="%1$s">%2$s</a></li>',
                wp_logout_url( get_permalink() ),
                'Log Out'
            );
        }
        // add search form
        $menu .= sprintf( '<li class="menu-item" id="search-form">%s</li>', __( genesis_search_form() ) );
        // if cart is not empty, show icon in header
        if ( defined( 'WC' ) && WC()->cart ) {
            if ( 0 != WC()->cart->get_cart_contents_count() ) {
                $menu .= sprintf( '<li class="menu-item cart" id="cart"><a href="%s"><span class="screen-reader-text">Cart</span>&nbsp;</a></li>', WC()->cart->get_cart_url() );
            }
        }
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

//* Add post footer widget
add_action( 'widgets_init', 'ora_add_post_footer_sidebar' );
function ora_add_post_footer_sidebar() {
    genesis_register_sidebar( array (
        'id'            => 'post-footer',
        'name'          => 'Post Footer',
        'description'   => __( 'Post Footer widget area.', 'genesischild' )
    ));
}
add_theme_support( 'genesis-footer-widgets', 4 );

//* Add additional sidebars
add_action( 'widgets_init', 'ora_add_sidebars' );
function ora_add_sidebars() {
    // Floating CTA
    genesis_register_sidebar( array (
        'id'            => 'floating-cta',
        'name'          => 'Floating Call-to-Action',
        'description'   => __( 'Call-to-Action bar on homepage.', 'genesischild' )
    ));

    // Membership Pages
    genesis_register_sidebar( array(
        'id'            => 'membership',
        'name'          => __( 'Membership Pages', 'genesischild' ),
        'description'   => __( 'This is a sidebar for membership pages', 'genesischild' ),
    ));

    // Products
    genesis_register_sidebar( array(
        'id'            => 'products',
        'name'          => __( 'Products', 'genesischild' ),
        'description'   => __( 'This is a sidebar for the guarantee on products', 'genesischild' ),
    ));
}

//* Add floating CTA JS
add_action( 'wp_enqueue_scripts', 'ora_enqueue_floating_cta_js' );
function ora_enqueue_floating_cta_js() {
    wp_register_script( 'floating-cta', get_stylesheet_directory_uri() . '/js/floating-cta.min.js', array( 'jquery', 'jquery-cookie' ) );
}

//* restrict homepage to 6 posts
add_action( 'pre_get_posts', 'ora_limit_homepage_posts' );
function ora_limit_homepage_posts( $query ) {
    if ( $query->is_main_query() && is_front_page() ) {
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
    add_image_size( 'tagged-content-thumb', 270, 270, true );
    add_image_size( 'targeted-landing-page-banner', 1400, 350, true );
    add_image_size( 'targeted-landing-page-featured', 339, 226, true );
}

//* Add custom pagination function for tagged-content pages
//* Adapted from http://callmenick.com/post/custom-wordpress-loop-with-pagination
function custom_pagination( $numpages = '', $pagerange = '', $paged = '', $fragment = '' ) {

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
        'add_fragment'          => $fragment,
    );

    $paginate_links = paginate_links( $pagination_args );

    if ( $paginate_links ) {
        echo '<nav class="custom-pagination">';
        echo $paginate_links;
        echo '</nav>';
    }
}

//* Customize post info display
add_filter( 'genesis_post_info', 'ora_post_info' );
function ora_post_info( $post_info ) {
    if ( is_single() ) {
        $post_info = 'on [post_date] by [post_author] [post_edit]';
    }

    return $post_info;
}

//* Customize post meta display
add_filter( 'genesis_post_meta', 'ora_post_meta' );
function ora_post_meta( $post_meta ) {
    global $post;
    if ( 'post' == get_post_type( get_the_ID( $post ) ) ) {
        $post_meta = '<section class="post-meta">
            <section class="categories">
                <h3 class="alternate">Categories</h3>
                [post_categories before=""]
            </section>
            <section class="tags">
                <h3 class="alternate">Tags</h3>
                [post_tags before=""]
            </section>
        </section>';
    }

    return $post_meta;
}

//* Add post footer sidebar
add_action( 'genesis_entry_footer', 'ora_post_footer_widgets' );
function ora_post_footer_widgets() {
    if ( is_single() ) {
        echo '<section id="post-footer" class="post-footer">';
        dynamic_sidebar( 'post-footer' );
        echo '</section>';
    }
}

//* Modify author box title
add_filter( 'genesis_author_box_title', 'ora_author_box_title' );
function ora_author_box_title() {
    return get_the_author();
}

//* Modify the author avatar size
add_filter( 'genesis_author_box_gravatar_size', 'ora_author_box_gravatar_size' );
function ora_author_box_gravatar_size( $size ) {
    return '100';
}

//* Modify the comment form button
add_filter( 'comment_form_defaults', 'ora_comment_form_button' );
function ora_comment_form_button( $defaults ) {
    $defaults['label_submit'] = __( 'Submit Comment', 'orawellness' );
    $defaults['class_submit'] = 'primary button bordered';

    return $defaults;
}

//* Declare Woocommerce support
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

//* Remove sidebars from product pages
add_filter( 'genesis_site_layout', 'ora_remove_sidebars_woocommerce' );
function ora_remove_sidebars_woocommerce() {
    if( function_exists( 'is_shop' ) && ( is_page( array( 'cart', 'checkout' )) || is_shop() || 'product' == get_post_type() ) ) {
        remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
        unregister_sidebar( 'sidebar-alt' );
        genesis_unregister_layout( 'content-sidebar' );

        return 'full-width-content';
    }
}

//* Hide product description header
add_filter( 'woocommerce_product_description_heading', function() { return false; } );

//* Add Ora Wellness logo to login page
function ora_login_page_styles() {
    echo '<link rel="stylesheet" href="' . get_stylesheet_directory_uri() . '/orawellness-login.css"/>';
    ora_theme_JS();
}
add_action( 'login_enqueue_scripts', 'ora_login_page_styles' );

//* Add custom content to login page header
function ora_add_login_page_header() {
    echo '<section class="login-container">
        <h1>Log in to your account</h1>';
}
add_filter( 'login_message', 'ora_add_login_page_header' );

//* Add custom content to login page footer
function ora_add_login_page_footer() {
    echo '</section><!-- .login-container -->';
}
add_action( 'login_footer', 'ora_add_login_page_footer' );

//* Remove default login page CSS and include custom fonts
function login_remove_scripts() {
    add_filter( 'style_loader_tag', '__return_null' );
    ora_theme_JS();
}
add_action( 'login_init', 'login_remove_scripts', 15 );

//* Add “logged-out” class to body for styling
function ora_add_logged_out_class( $classes ) {
    if ( ! is_user_logged_in() ) {
        $classes[] = 'logged-out';
    }
    return $classes;
}
add_filter( 'body_class', 'ora_add_logged_out_class' );

//* Add function for outputting testimonials
function ora_show_testimonials( $number_of_posts, $home = NULL, $additional_query_args = array() ) {
    // WP_Query arguments
    $args = array (
        'post_type'              => array( 'testimonial' ),
        'pagination'             => false,
        'posts_per_page'         => $number_of_posts,
        'orderby'                => 'menu_order',
        'order'                  => 'ASC',
    );

    // home page sticky posts
    if ( 'true' == $home ) {
        $args['meta_query'] = array(
            array(
                'key'           => 'show_on_home',
                'value'         => '1',
            )
        );
    }

    // merge input args
    if ( $additional_query_args ) {
        $args = array_merge( $args, $additional_query_args );
    }

    // The Query
    $testimonial_query = new WP_Query( $args );

    // The Loop
    if ( $testimonial_query->have_posts() ) {
        echo '<section class="testimonials">
        <section class="testimonials-inner wrap">';

        while ( $testimonial_query->have_posts() ) {
            $testimonial_query->the_post();

            echo '<article class="testimonial single">';
            // content
            echo '<div class="testimonial-content-wrapper"><div class="testimonial-content">' . apply_filters( 'the_content', get_the_content() ) . '</div>';

            echo '</article>';
        }
        echo '</section>
        </section>';
    }

    // Restore original Post Data
    wp_reset_postdata();
}

//* Add member info widget
class MemberInfoWidget extends WP_Widget {
    function __construct() {
        // instantiate the parent object
        parent::__construct( false, 'Member Info Widget' );
    }
    function widget( $args, $instance ) {
        // widget output
        global $current_user;
        echo '<section id="member-info" class="widget member-info">
            <div class="widget-wrap">
                ' . get_avatar( $current_user->data->ID, 150 ) . '
                <h3>' . $current_user->data->display_name . '</h3>
                <p class="alternate">' . str_replace( '@', '@<wbr>', $current_user->user_email ) . '</p>
                <p><strong><a href="' . home_url( '/membership-home/' ) . '">Membership Home</a></strong></p>
                <p><strong><a href="' . home_url( '/my-account/' ) . '">My Profile</a></strong></p>
                <p><strong><a href="' . wp_logout_url( home_url() ) . '">Log Out</a></strong></p>
            </div>
        </section>';
    }
}
function ora_member_info_widget() {
    register_widget( 'MemberInfoWidget' );
}
add_action( 'widgets_init', 'ora_member_info_widget' );

//* Show custom page widgets
function ora_show_custom_widget() {
    // customized opt-in form
    if ( 'custom' == get_field( 'show_opt-in' ) && get_field( 'optin_title' ) && get_field( 'optin_form_html' ) ) { ?>
        <section class="widget page-customized-offer">
            <div class="widget-wrap">
                <h3 class="widgettitle widget-title"><?php echo wptexturize( get_field( 'optin_title' ) ); ?></h3>
                <div class="textwidget">
                    <?php if ( get_field( 'optin_subtitle' ) ) { echo wptexturize( get_field( 'optin_subtitle' ) ); } ?>
                    <?php echo do_shortcode( get_field( 'optin_form_html' ) ); ?>
                </div>
            </div>
        </section>
    <?php } elseif ( 'generic' == get_field( 'show_opt-in' ) && get_field( 'generic_membership_opt-in' ) ) { ?>
        <section class="widget page-customized-offer">
            <div class="widget-wrap">
                <div class="textwidget">
                    <?php echo do_shortcode( get_field( 'generic_membership_opt-in' ) ); ?>
                </div>
            </div>
        </section>
    <?php }

    // customized product
    if ( get_field( 'show_featured_product' ) && get_field( 'featured_product_url' ) ) {
        $custom_title = get_field( 'featured_product_title' );
        $featured_product = get_field( 'featured_product_url' );
        $featured_image = get_field( 'featured_product_image' );
    ?>
        <section class="widget page-customized-product">
            <div class="widget-wrap">
                <h3 class="widgettitle widget-title"><?php
                    if ( $custom_title ) {
                       echo $custom_title;
                    } else {
                        echo 'Featured Product';
                    }
                    ?></h3>
                <div class="textwidget">
                    <?php
                        if ( $featured_image ) {
                            echo wp_get_attachment_image( $featured_image, 'shop_thumbnail', false, array( 'class' => 'alignright' ) );
                        }
                        if ( get_field( 'featured_product_description' ) ) {
                            the_field( 'featured_product_description' );
                        }
                        echo '<a href="' . get_field( 'featured_product_url' ) . '" class="clear button bordered cta center">Learn More</a>';
                    ?>
                </div>
            </div>
        </section>
    <?php wp_reset_postdata();
    }

    // customized testimonial
    if ( get_field( 'show_featured_testimonial' ) && get_field( 'featured_testimonial' ) ) {
        $this_testimonial = get_field( 'featured_testimonial' );
        ?>
        <section class="widget testimonial">
            <div class="widget-wrap">
                <div class="textwidget">
                <?php echo do_shortcode( '[testimonial id="' . $this_testimonial->ID . '" content_only="true"]' ); ?>
                </div>
            </div>
        </section>
    <?php }

    // customized testimonial category
    if ( get_field( 'show_categorized_testimonials' ) && get_field( 'testimonials_category' ) ) {
        $taxonomy_id = get_field( 'testimonials_category' );
        $posts_per_page = get_field( 'number_of_testimonials' );

        // WP_Query arguments
        $testimonial_category_args = array (
            'post_type'              => array( 'testimonial' ),
            'posts_per_page'         => $posts_per_page,
            'orderby'                => 'rand',
            'tax_query'              => array(
                array (
                    'taxonomy'  => 'testimonial-category',
                    'field'     => 'term_id',
                    'terms'     => $taxonomy_id,
                )),
            );

        // The Query
        $testimonial_category_query = new WP_Query( $testimonial_category_args );

        if ( $testimonial_category_query->have_posts() ) { ?>
            <section class="widget testimonial">
                <div class="widget-wrap">
                    <div class="textwidget">
            <?php
            while ( $testimonial_category_query->have_posts() ) {
                $testimonial_category_query->the_post();

                echo '<article class="testimonial single">
                    <div class="testimonial-content-wrapper"><div class="testimonial-content">' . apply_filters( 'the_content', get_the_content() ) . '</div>
                </article>';
            }
            // CTA
            if ( $taxonomy_id ) {
                $related_product = get_term_meta( $taxonomy_id, 'related-product', true );
                if ( $related_product ) {
                    echo '<p><a class="button primary center" href="' . get_permalink( $related_product ) . '">Learn More</a></p>';
                }
            } ?>
                    </div>
                </div>
            </section>
        <?php
        }

        // Restore original Post Data
        wp_reset_postdata();
    }
}
add_action( 'genesis_before_sidebar_widget_area', 'ora_show_custom_widget' );

//* Add favicons
add_filter( 'genesis_pre_load_favicon', function() { return NULL; } );
add_action( 'wp_head', 'ora_custom_favicons' );
function ora_custom_favicons() {
    echo '    <link rel="apple-touch-icon" sizes="57x57" href="' . get_stylesheet_directory_uri() . '/images/favicons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="' . get_stylesheet_directory_uri() . '/images/favicons/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="' . get_stylesheet_directory_uri() . '/images/favicons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="' . get_stylesheet_directory_uri() . '/images/favicons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="' . get_stylesheet_directory_uri() . '/images/favicons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="' . get_stylesheet_directory_uri() . '/images/favicons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="' . get_stylesheet_directory_uri() . '/images/favicons/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="' . get_stylesheet_directory_uri() . '/images/favicons/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="' . get_stylesheet_directory_uri() . '/images/favicons/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="' . get_stylesheet_directory_uri() . '/images/favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="' . get_stylesheet_directory_uri() . '/images/favicons/favicon-194x194.png" sizes="194x194">
    <link rel="icon" type="image/png" href="' . get_stylesheet_directory_uri() . '/images/favicons/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="' . get_stylesheet_directory_uri() . '/images/favicons/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="' . get_stylesheet_directory_uri() . '/images/favicons/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="' . get_stylesheet_directory_uri() . '/images/favicons/manifest.json">
    <link rel="mask-icon" href="' . get_stylesheet_directory_uri() . '/images/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="theme-color" content="#6ba26f">
    ';
}

//* Prevent YouTube Related Content
add_filter( 'oembed_result', 'ora_fix_oembed' );
function ora_fix_oembed( $embed ) {
   return str_replace( '?feature=oembed', '?feature=oembed&rel=0', $embed );
}

/*
 * Gets the excerpt of a specific post ID or object
 * From https://pippinsplugins.com/a-better-wordpress-excerpt-by-id-function/
 * @param - $post - object/int - the ID or object of the post to get the excerpt of
 * @param - $length - int - the length of the excerpt in words
 * @param - $tags - string - the allowed HTML tags. These will not be stripped out
 * @param - $extra - string - text to append to the end of the excerpt
 */
function pippin_excerpt_by_id( $post, $length = 55, $tags = '<a><em><strong>', $extra = '&hellip;' ) {

    if( is_int( $post ) ) {
        // get the post object of the passed ID
        $post = get_post($post);
    } elseif( !is_object( $post ) ) {
        return false;
    }

    if( has_excerpt( $post->ID ) ) {
        $the_excerpt = $post->post_excerpt;
        return apply_filters( 'the_content', $the_excerpt );
    } else {
        $the_excerpt = $post->post_content;
    }

    $the_excerpt = strip_shortcodes( strip_tags( $the_excerpt ), $tags );
    $the_excerpt = preg_split( '/\b/', $the_excerpt, $length * 2+1 );
    $excerpt_waste = array_pop( $the_excerpt );
    $the_excerpt = implode( $the_excerpt );
    $the_excerpt .= $extra;

    return apply_filters( 'the_content', $the_excerpt );
}

//* Display 12 products per page
add_filter( 'loop_shop_per_page', function() { return 12; }, 20 );

//* Set “continue shopping” link
add_filter( 'woocommerce_continue_shopping_redirect', 'ora_get_wc_shop_page' );
function ora_get_wc_shop_page() {
    return get_permalink( woocommerce_get_page_id( 'shop' ) );
}

//* inline JS
function ora_add_inline_js() {
    // definitely not the recommended method, but the plugin doesn’t use the enqueue method, so can’t use it as a dependency
    echo '<script type="text/javascript">jQuery(document).ready(function(){jQuery(\'.switch\').addClass(\'open\');jQuery(\'form.woocommerce-checkout\').off(\'submit\');});</script>';
}
add_action( 'wp_footer', 'ora_add_inline_js', 25 );

//* Set email logo size
function tweak_woocommerce_email_header( $email_heading ) {
    echo '<style type="text/css">
        #template_header_image img { width: 275px !important; }
    </style>';
}
add_action( 'woocommerce_email_header', 'tweak_woocommerce_email_header' );

//* Change Woocommerce product tabs and add KB articles and testimonials
add_filter( 'woocommerce_product_tabs', 'edit_woocommerce_tabs', 98 );
function edit_woocommerce_tabs( $tabs ) {
    // change description
    $tabs['additional_information']['title'] = 'Product Information';

    if ( get_field('related_kb_articles') ) {
        // add FAQs
        $tabs['faq'] = array(
            'title'     => 'FAQs',
            'priority'  => 50,
            'callback'  => 'woocommerce_product_faqs_tab_content',
        );
    }

    if ( get_field('testimonials_category') ) {
        // add testmionials
        $tabs['testimonial'] = array(
            'title'     => 'Testimonials',
            'priority'  => 40,
            'callback'  => 'woocommerce_product_testimonials_tab_content',
        );
    }

    // disable reviews
    unset($tabs['reviews']);
    return $tabs;
}
function woocommerce_product_faqs_tab_content() {
    wp_enqueue_script( 'product-faq' );

    echo '<h2>FAQs</h2>';
    foreach( get_field('related_kb_articles') as $this_faq ) {
        echo '<p><a class="kb-header" href="' . get_permalink( $this_faq->ID ) . '" target="_blank">' . $this_faq->post_title . '</a></p>
        <article class="kb-content">' . apply_filters( 'the_content', $this_faq->post_content ) . '</article>';
    }
    echo '</ul>';
}
function woocommerce_product_testimonials_tab_content() {
    $product_testimonial_args = array(
        'tax_query' => array(
            array(
                'taxonomy'  => 'testimonial-category',
                'field'     => 'term_id',
                'terms'     => get_field( 'testimonials_category' ),
            ),
        ),
    );

    echo '<h2>Testimonials</h2>';
    ora_show_testimonials( -1, false, $product_testimonial_args );
}

//* Add JS to handle product FAQs
function add_product_faq_js() {
    wp_register_script( 'product-faq', get_stylesheet_directory_uri() . '/js/product-faq.min.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'add_product_faq_js' );

//* Add .testimonial-thumb to all testimonial images
add_filter('wp_get_attachment_image_attributes', 'ora_testimonial_image_class_filter');
function ora_testimonial_image_class_filter( $attr ) {
    global $post;
    if ( 'testimonial' == $post->post_type ) {
        $attr['class'] .= ' testimonial-thumb';
    }

    return $attr;
}

//* Add products sidebar
add_action( 'woocommerce_after_single_product_summary', 'output_products_sidebar', 18 );
function output_products_sidebar() {
    echo '<section id="products" class="sidebar products"><section class="wrap">';
    dynamic_sidebar( 'products' );
    echo '</section></section>';
}

//* Hide author and date on testimonials
add_filter( 'genesis_post_info', 'testimonial_post_info_filter' );
function testimonial_post_info_filter( $post_info ) {
    if ( !is_page() && 'testimonial' == get_post_type() ) {
        return NULL;
    }
}

//* Add wrappers to KBE sidebar widgets
add_filter( 'kbe_sidebar_before_widget', function(){ return '<section id="%1$s" class="widget %2$s">'; } );
add_filter( 'kbe_sidebar_after_widget', function() { return '</section>'; }  );

//* Add hero image to product archive
add_action( 'woocommerce_before_main_content', 'ora_shop_hero_image', 15 );
function ora_shop_hero_image() {
    $shop_page_ID = get_option( 'woocommerce_shop_page_id' );
    if ( is_archive() && get_field( 'hero_image', $shop_page_ID ) ) {
        echo wp_get_attachment_image( get_field( 'hero_image', $shop_page_ID )['id'], 'targeted-landing-page-banner' );
    }
}

//* Remove product count and sorting dropdown
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

//* Set video archive thumbnail size
add_filter( 'genesis_pre_get_image', 'ora_video_tutorial_thumbnail', 10, 3 );
function ora_video_tutorial_thumbnail( $false, $args, $post ) {
    if ( has_post_thumbnail( $args['post_id'] ) && ( 0 === $args['num'] ) ) {
        $id = get_post_thumbnail_id( $args['post_id'] );
    }
    //* Else if the first (default) image attachment is the fallback, use its id
    elseif ( 'first-attached' === $args['fallback'] ) {
        $id = genesis_get_image_id( $args['num'], $args['post_id'] );
    }
    //* Else if fallback id is supplied, use it
    elseif ( is_int( $args['fallback'] ) ) {
        $id = $args['fallback'];
    }

    //* If we have an id, get the html and url
    if ( isset( $id ) ) {
        if ( 'video_tutorial' == $post->post_type ) {
            $html = wp_get_attachment_image( $id, array( 250, 250 ), false, $args['attr'] );
        } else {
            $html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
        }
        list( $url ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
        return $html;
    }
    //* Else, return false (no image)
    else {
        return false;
    }
}

//* Remove Blog header from archive page
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
remove_action( 'genesis_before_loop', 'genesis_do_blog_template_heading' );
remove_action( 'genesis_before_loop', 'genesis_do_posts_page_heading' );

//* Tweak thank-you page title
add_filter( 'the_title', 'ora_thankyou_title', 10, 2 );
function ora_thankyou_title( $title, $id ) {
    if ( function_exists( 'is_order_received_page' ) && is_order_received_page() && get_the_ID() === $id ) {
        $title = 'Order Success!';
    }
    return $title;
}

//* Tweak thank-you page text
add_filter( 'woocommerce_thankyou_order_received_text', 'ora_thankyou_text' );
function ora_thankyou_text( $content ) {
    return 'Thank you for your order! In a moment, you will receive an email confirming your order success as well as an email within the next day or two alerting you that your order has shipped and is on its way to you!</p>
    <p class="woocommerce-thankyou-order-received">We look forward to hearing how you benefit from using our products.</p>
    <p class="woocommerce-thankyou-order-received">In the meantime, feel free to gather more info from our free videos tutorials and articles.  We also invite you to sign up for our OraWellness Aloha Club, a membership where you get <strong>free</strong> access to expert interviews and a resource guide library full of helpful e-books. <a href="' . home_url( '/membership-signup/' ) . '">Sign up</a> for your free Aloha Club membership!';
}

//* Add “continue shopping” button next to “Update cart”
add_action( 'woocommerce_cart_actions', 'ora_continue_shopping_button' );
function ora_continue_shopping_button() {
    echo '<a class="button continue-shopping" href="' . get_permalink( woocommerce_get_page_id( 'shop' ) ) . '">Continue Shopping</a>';
}

//* Modify lost password reset page
add_action( 'woocommerce_lost_password_message', 'ora_lost_password_message' );
function ora_lost_password_message( $message ) {
    if ( 'true' == $_GET['reset-link-sent'] ) {
        $message = 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset. If you don&rsquo;t see it in your inbox, please check your spam folder.</p>
        <p><a href="/my-account/">Login to Aloha Club here</a>';
    } else {
        $message = 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.';
    }
    return $message;
}

//* Add customer note prefix for Insfusionsoft’s sake
add_action( 'woocommerce_payment_complete', 'ora_add_customer_note_prefix' );
function ora_add_customer_note_prefix( $id ) {
    global $woocommerce;
    $order = new WC_Order( $id );

    // if not already added, prefix customer message
    if ( $order->customer_message &&  strpos( $order->customer_message, 'Customer note: ' ) === false ) {
        $updated_note = array(
            'ID'            => $id,
            'post_excerpt'  => 'Customer note: ' . $order->customer_message,
        );

        $updated_post = wp_update_post( $updated_note );
    }
}

//* Remove order notes
add_filter( 'woocommerce_checkout_fields', 'ora_order_notes' );
function ora_order_notes( $checkout_fields ) {
    unset($checkout_fields['order']['order_comments']);
    return $checkout_fields;
}

//* Replace order notes with message
add_action( 'woocommerce_before_order_notes', 'ora_order_notes_message' );
function ora_order_notes_message() {
    echo '<h2>Order Notes</h2>
    <p>Do you have a special request for this order? Please <a target="_blank" href="https://www.orawellness.com/contact/">contact us</a> before placing your order so we can help.</p>';
}

/**
 * Set ACF local JSON save directory
 * @param  string $path ACF local JSON save directory
 * @return string ACF local JSON save directory
 */
add_filter( 'acf/settings/save_json', 'ora_acf_json_save_point' );
function ora_acf_json_save_point( $path ) {
    return get_stylesheet_directory() . '/acf-json';
}

/**
 * Set ACF local JSON open directory
 * @param  array $path ACF local JSON open directory
 * @return array ACF local JSON open directory
 */
add_filter( 'acf/settings/load_json', 'ora_acf_json_load_point' );
function ora_acf_json_load_point( $path ) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
}
