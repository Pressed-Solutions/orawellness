<?php
/**
* Description: The page used for the testimonials archive
*/


// add loop for testimonials
add_action( 'genesis_after_entry', 'ora_testimonial_loop', 5 );
function ora_testimonial_loop() {
    if ( get_query_var( 'paged' ) ) {
        $paged = get_query_var( 'paged' );
    } else {
        $paged = 1;
    }

    // WP_Query arguments
    $args = array (
        'post_type'              => array( 'testimonial' ),
        'posts_per_page'         => '10',
        'pagination'             => true,
        'paged'                  => $paged,
        'order'                  => 'ASC',
        'orderby'                => 'menu_order modified',
    );

    // The Query
    $testimonial_query = new WP_Query( $args );

    // The Loop
    if ( $testimonial_query->have_posts() ) {
        echo '<section class="custom-archive testimonial">';
        while ( $testimonial_query->have_posts() ) {
            $testimonial_query->the_post();

            ob_start();
            the_content();
            $content = ob_get_clean();

            echo '<article class="testimonial single clearfix">';
                // content
                echo '<p class="testimonial-content">' . $content . '</p><p class="testimonial-title alternate">';

                // post thumbnail
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail( 'testimonial-thumb', array( 'class' => 'testimonial-thumb alignleft' ) );
                }

                echo get_the_title();
                if ( get_field( 'personal_info_location' ) ) {
                    echo ', ' . get_field( 'personal_info_location' );
                }
            echo '</p></article>';

            // add “share” box
            if ( ( $testimonial_query->current_post + 1 ) == 4 ) {
                echo '<article class="testimonial single share">
                    <div class="wrapper">
                        <h3>Do you have an OraWellness success story to share with&nbsp;us?</h3>
                        <p><a href="/testimonial-submit/" class="button bordered">Share Your Story</a></p>
                    </div>
                </article>';
            }
        }
        custom_pagination( $testimonial_query->max_num_pages, "", $paged );
        echo '</section><!-- .custom-archive.testimonial -->';
    }

    // Restore original Post Data
    wp_reset_postdata();
}

genesis();
