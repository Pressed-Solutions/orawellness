<?php
/**
* Template Name: Tagged Product Template
* Description: Used as a page template for pages showing products with specific tags
*/

// Add page subtitle
add_filter( 'genesis_post_title_text', 'ora_tagged_content_subheading' );
function ora_tagged_content_subheading( $title ) {
    if ( get_field( 'page_subtitle' ) ) {
        $title .= '<h2 class="entry-subtitle">' . get_field( 'page_subtitle' ) . '</h2>';
    }

    return $title;
}

// Add loop for tagged posts
add_action( 'genesis_after_loop', 'ora_tagged_posts_loop', 7 );
function ora_tagged_posts_loop() {
    if ( get_field( 'specific_product' ) ) {
        // handle paging
        if ( get_query_var( 'paged' ) ) {
            $paged = get_query_var( 'paged' );
        } else {
            $paged = 1;
        }

        // build array of products
        $product_array = array();
        foreach( get_field( 'specific_product' ) as $this_product ) {
            $product_array[] = $this_product->ID;
        }

        // WP_Query arguments
        $tagged_posts_args = array (
            'post_type'              => array( 'product' ),
            'post__in'               => $product_array,
            'pagination'             => true,
            'posts_per_page'         => '3',
            'paged'                  => $paged,
        );

        // The Query
        $tagged_posts_query = new WP_Query( $tagged_posts_args );

        // The Loop
        if ( $tagged_posts_query->have_posts() ) {

            // output content
            echo '<section class="tagged-content">
            <h2 class="alternate" id="related-products">Related Products</h2>';

            while ( $tagged_posts_query->have_posts() ) {
                $tagged_posts_query->the_post();
                echo '<article class="tagged-content-article"><h3 class="title"><a href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(), array( 160, 160 ), array( 'class' => 'alignright' ) ) . get_the_title() . '</a></h3>' . get_the_excerpt() . '</article>';
            }

            custom_pagination( $tagged_posts_query->max_num_pages, "", $paged, '#related-products' );
            echo '</section>';
        }

        // Restore original Post Data
        wp_reset_postdata();
    }
}


genesis();
