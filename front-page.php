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

// add “Recent Posts” header
add_action( 'genesis_before_content', 'ora_home_header' );
function ora_home_header() {
    echo '<h2 class="home-header alternate">Recent Posts</h2>';
}

// hide post info and meta
add_filter( 'genesis_post_info', function() { return false; } );
add_filter( 'genesis_post_meta', function() { return false; } );

// remove pagination
remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );

genesis();
