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
    echo '<h2 class="post-grid-header alternate">Top Posts</h2>';
}

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'ora_frontpage_grid_helper' );
function ora_frontpage_grid_helper() {

    // get featured posts from ACF
    if ( get_field( 'featured_posts' ) ) {
        $featured_posts_array = array();
        foreach( get_field( 'featured_posts' ) as $this_post ) {
            $featured_posts_array[] = $this_post->ID;
        }
    }

    // WP_Query arguments
    $recent_posts_args = array (
        'pagination'        => false,
        'posts_per_page'    => 6,
    );

    // add featured posts
    if ( ! empty( $featured_posts_array ) ) {
        $recent_posts_args['post__in'] = $featured_posts_array;
    }

    // The Query
    $recent_posts_query = new WP_Query( $recent_posts_args );

    // The Loop
    if ( $recent_posts_query->have_posts() ) {
        echo '<main class="content post-grid" id="genesis-content">';
        while ( $recent_posts_query->have_posts() ) {
            $recent_posts_query->the_post();
            echo '<article class="post-' . get_the_ID() . ' ' . get_post_type() . ' type-' . get_post_type() . ' status-publish format-standard ' . ( has_post_thumbnail() ? 'has-post-thumbnail' : '' ) . ' entry" itemscope="" itemtype="http://schema.org/CreativeWork">';
            echo '<header class="entry-header"><h2 class="entry-title" itemprep="headline"><a href="' . get_permalink() . '" rel="bookmark">' . get_the_title() . '</a></h2></header>';
            echo '<div class="entry-content" itemprop="text">';
            if ( has_post_thumbnail() ) {
                echo '<a class="entry-image-link" href="' . get_permalink() . '" aria-hidden="true">' . wp_get_attachment_image( get_post_thumbnail_id(), 'thumbnail', false, array( 'class' => 'alignright post-image entry-image' ) ) . '</a>';
            }
            echo get_the_excerpt();
            echo '</div>';
            echo '</article>';
        }
        echo '</main>';
    }

    // Restore original Post Data
    wp_reset_postdata();
}

// add .post-grid class to posts
add_filter( 'genesis_attr_content', 'ora_add_post_grid_class' );
function ora_add_post_grid_class( $attributes ) {
    $attributes['class'] .= ' post-grid';

    return $attributes;
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
            $onclick = NULL;
            $eventAction = get_field( 'eventaction' );
            $eventValue = get_field( 'eventvalue' );
            if ( $eventAction && $eventValue ) {
                $onclick = " onclick=\"__gaTracker('send', 'event', {eventCategory: 'Home - Funnel', eventAction: '$eventAction', eventValue: '$eventValue'});\"";
            }

            return '&hellip;<a class="more-link button bordered white-color" href="' . get_permalink() . '" ' . $onclick . '>Learn More</a>';
        }

        // output content
        echo '<section class="quick-links">
        <h2 class="home-header alternate">I Need Help With&hellip;</h2>
        <section class="quick-links-inner wrap">';
        while ( $need_help_query->have_posts() ) {
            $need_help_query->the_post();
            echo '<article class="quick-links-article"><h3 class="title">' . get_the_title() . '</h3>' . get_the_excerpt();
            if ( strpos( get_the_excerpt(), get_permalink() ) === false ) {
                echo ora_quick_links_read_more_link();
            }
            echo '</article>';
        }
        echo '</section>
        </section>';
    }

    // Restore original Post Data
    wp_reset_postdata();
}

// add floating CTA bar
//add_action( 'genesis_before_footer', 'ora_add_cta', 9 );
function ora_add_cta() {
    echo '<section id="floating-cta" class="floating-cta"><section class="wrap">';
    dynamic_sidebar( 'floating-cta' );
    echo '<a class="close">&times;</a>
    </section></section>';
    wp_enqueue_script( 'floating-cta' );
}

genesis();
