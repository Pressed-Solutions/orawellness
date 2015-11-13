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

// add loop for testimonials
add_action( 'genesis_before_footer', 'ora_testimonial_loop', 5 );
function ora_testimonial_loop() {
    // WP_Query arguments
    $args = array (
        'post_type'              => array( 'testimonial' ),
        'pagination'             => false,
        'posts_per_page'         => '4',
    );

    // The Query
    $testimonial_query = new WP_Query( $args );

    // The Loop
    if ( $testimonial_query->have_posts() ) {
        echo '<section class="testimonials">
        <section class="testimonials-inner wrap">';

        while ( $testimonial_query->have_posts() ) {
            $testimonial_query->the_post();

            echo '<article class="testimonial single">';
            // post thumbnail
            if ( has_post_thumbnail() ) {
                the_post_thumbnail( array( 80, 80 ), array( 'class' => 'testimonial-thumb' ) );
            }

            // content
            echo '<div class="testimonial-content-wrapper"><p class="testimonial-content">' . wptexturize( get_the_content() ) . '</p><p class="testimonial-title alternate">' . get_the_title() . ', ' . get_post_meta( get_the_ID(), 'personal_info_location', true ) . '</div>';

            echo '</article>';
        }
        echo '</section>
        </section>';
    }

    // Restore original Post Data
    wp_reset_postdata();
}

genesis();
