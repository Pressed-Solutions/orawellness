<?php
/**
* Template Name: Home Page Template
* Description: Used as a page template to show recent posts followed by testimonials and other content
*/

// remove sidebar
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

// display full-width
add_filter( 'genesis_pre_get_option_site_layout', 'ora_home_layout' );
/**
 * Force layout
 *
 * @author Greg Rickaby
 * @since 1.0.0
 */
function ora_home_layout( $opt ) {
    $opt = 'full-width-content'; // You can change this to any Genesis layout
    return $opt;
}

// add header photo/text
add_action( 'genesis_after_header', 'ora_home_header', 5 );
function ora_home_header() {
    $frontpage_ID = get_page_by_title( 'home' )->ID;
    echo '<div class="home-header"><div class="wrap">';
    if ( get_field( 'title', $frontpage_ID ) ) {
        echo '<h1 class="title">' . get_field( 'title', $frontpage_ID ) . '</h1>';
    }
    if ( get_field( 'subtitle', $frontpage_ID ) ) {
        echo '<h1 class="subtitle">' . get_field( 'subtitle', $frontpage_ID ) . '</h1>';
    }
    if ( get_field( 'description', $frontpage_ID ) ) {
        echo '<p class="description">' . get_field( 'description', $frontpage_ID ) . '</p>';
    }
    echo '</div></div>';
}

// add “Recent Posts” header
add_action( 'genesis_before_content', 'ora_recent_posts_header' );
function ora_recent_posts_header() {
    echo '<h2 class="home-header alternate">Recent Posts</h2>';
}

// hide post info and meta
add_filter( 'genesis_post_info', function() { return false; } );
add_filter( 'genesis_post_meta', function() { return false; } );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

// remove pagination
remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );

// add loop for testimonials
add_action( 'genesis_before_footer', 'ora_testimonial_loop', 5 );
function ora_testimonial_loop() {
    ora_show_testimonials( 4, true );
}

// add loop for need help section
add_action( 'genesis_before_footer', 'ora_need_help_loop', 7 );
function ora_need_help_loop() {
    // WP_Query arguments
    $args = array (
        'post_type'              => array( 'page' ),
        'category_name'          => 'quick-links',
        'pagination'             => false,
        'posts_per_page'         => '5',
    );

    // The Query
    $need_help_query = new WP_Query( $args );

    // The Loop
    if ( $need_help_query->have_posts() ) {
        // change length of excerpt
        function custom_excerpt_length( $length ) {
            return 15;
        }
        add_filter( 'excerpt_length', 'custom_excerpt_length' );

        // change “read more” text to buttons
        add_filter( 'excerpt_more', 'ora_quick_links_read_more_link' );
        function ora_quick_links_read_more_link() {
            return '&hellip;<a class="more-link button bordered white-color" href="' . get_permalink() . '">Read More</a>';
        }

        // output content
        echo '<section class="quick-links">
        <h2 class="home-header alternate">I Need Help With&hellip;</h2>
        <section class="quick-links-inner wrap">';
        while ( $need_help_query->have_posts() ) {
            $need_help_query->the_post();
            echo '<article class="quick-links-article"><h3 class="title">' . get_the_title() . '</h3>' . get_the_excerpt() . '</article>';
        }
        echo '</section>
        </section>';
    }

    // Restore original Post Data
    wp_reset_postdata();
}

// add floating CTA bar
add_action( 'genesis_before_footer', 'ora_add_cta', 9 );
function ora_add_cta() {
    echo '<section id="floating-cta" class="floating-cta"><section class="wrap">';
    dynamic_sidebar( 'floating-cta' );
    echo '</section></section>';
}

genesis();
