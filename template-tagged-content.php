<?php
/**
* Template Name: Tagged Content Template
* Description: Used as a page template for pages showing articles with specific tags
*/

// Add page subtitle
add_filter( 'genesis_post_title_text', 'ora_tagged_content_subheading' );
function ora_tagged_content_subheading( $title ) {
    if ( get_field( 'page_subtitle' ) ) {
        $title .= '<h2 class="entry-subtitle">' . get_field( 'page_subtitle' ) . '</h2>';
    }

    return $title;
}

// Add featured image
add_action( 'genesis_entry_content', 'ora_show_thumbnail', 8 );
function ora_show_thumbnail() {
    if ( has_post_thumbnail() ) {
        the_post_thumbnail( 'tagged-content-thumb', array( 'class' => 'alignright', '' ) );
    }
}

// Add loop for tagged posts
add_action( 'genesis_after_loop', 'ora_tagged_posts_loop', 7 );
function ora_tagged_posts_loop() {
    if ( get_field( 'post_tag' ) ) {
        $post_tag = esc_attr( get_field( 'post_tag' )->slug );
        if ( get_query_var( 'paged' ) ) {
            $paged = get_query_var( 'paged' );
        } else {
            $paged = 1;
        }

        // WP_Query arguments
        $tagged_posts_args = array (
            'post_type'              => array( 'post' ),
            'tag'                    => $post_tag,
            'pagination'             => true,
            'posts_per_page'         => '3',
            'paged'                  => $paged,
            'orderby'                => array(
                'meta_value_num' => 'DESC',
                'date' => 'DESC',
            ),
            'meta_query'             => array(
                'relation'  => 'OR',
                array(
                    'key'       => 'pin_to_top',
                    'value'     => array( '0', '1', ),
                    'compare'   => 'IN',
                ),
                array(
                    'key'       => 'pin_to_top',
                    'compare'   => 'NOT EXISTS',
                ),
            ),
        );

        // The Query
        $tagged_posts_query = new WP_Query( $tagged_posts_args );

        // The Loop
        if ( $tagged_posts_query->have_posts() ) {

            // output content
            echo '<section class="tagged-content">
            <h2 class="alternate">Recent Posts <em>about</em> ' . esc_attr( get_field( 'post_tag' )->name ) . '</h2>';

            while ( $tagged_posts_query->have_posts() ) {
                $tagged_posts_query->the_post();
                echo '<article class="tagged-content-article"><h3 class="title"><a href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(), array( 160, 160 ), array( 'class' => 'alignright' ) ) . get_the_title() . '</a></h3>' . get_the_excerpt() . '</article>';
            }

            custom_pagination( $tagged_posts_query->max_num_pages, "", $paged );
            echo '</section>';
        }

        // Restore original Post Data
        wp_reset_postdata();
    }
}


genesis();
