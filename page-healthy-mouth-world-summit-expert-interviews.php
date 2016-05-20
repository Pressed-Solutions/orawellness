<?php
/**
 * Description: The page used for the summit interviews archive
 */

// add loop for testimonials
add_action( 'genesis_after_entry', 'ora_summit_interview_loop', 5 );
function ora_summit_interview_loop() {
    if ( get_query_var( 'paged' ) ) {
        $paged = get_query_var( 'paged' );
    } else {
        $paged = 1;
    }

    // WP_Query arguments
    $args = array (
        'post_type'              => array( 'summit_interview' ),
        'posts_per_page'         => '5',
        'pagination'             => true,
        'paged'                  => $paged,
        'orderby'                => array(
            'menu_order'    => 'ASC',
            'date'          => 'DESC',
        ),
    );

    // The Query
    $summit_interview_query = new WP_Query( $args );

    // Search form
    echo '<form method="get" action="' . get_option( 'home' ) . '/" class="search-form" >
        <input type="search" value="'. $search_text .'" name="s" placeholder="Search summit interviews" />
        <input type="hidden" name="post_type" value="summit_interview" />
        <input type="submit" value="Search summit interviews" />
    </form>
    ';

    // The Loop
    if ( $summit_interview_query->have_posts() ) {
        echo '<section class="custom-archive expert-interview">';
        while ( $summit_interview_query->have_posts() ) {
            $summit_interview_query->the_post();

            echo '<article class="expert-interview single clearfix">';
                // post thumbnail
                if ( get_field( 'photo' ) ) {
                    echo '<a href="' . get_permalink() . '">' . wp_get_attachment_image( get_field( 'photo' ), 'testimonial-thumb', true, array( 'class' => 'alignright avatar' ) ) . '</a>';
                }

                // title
                echo '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
                // content
                echo '<p class="expert-interview-content">';
                if ( get_field( 'introduction' ) ) {
                    the_field( 'introduction' );
                } else {
                    the_excerpt();
                }
                echo '</p>';
            echo '</article>';
        }
        custom_pagination( $summit_interview_query->max_num_pages, "", $paged );
        echo '</section><!-- .custom-archive.expert-interview -->';
    }

    // Restore original Post Data
    wp_reset_postdata();
}

// Add page subtitle and banner
add_filter( 'genesis_post_title_text', 'ora_tagged_content_subheading' );
function ora_tagged_content_subheading( $title ) {
    if ( get_field( 'page_subtitle' ) ) {
        $title = '<h1 class="entry-title">' . get_field( 'page_subtitle' ) . '</h1>';
    }

    if ( get_field( 'header_image' ) ) {
        $title .= '<style>.entry-header { background-image: url(\'' . get_field( 'header_image' ) . '\'); } </style>';
    }

    return $title;
}

// Add membership sidebar
add_action( 'genesis_after_content', 'ora_show_membership_sidebar' );
function ora_show_membership_sidebar() {
    echo '<aside class="sidebar sidebar-membership widget-area" role="complementary" aria-label="Membership Pages Sidebar" itemscope itemtype="http://schema.org/WPSideBar" id="genesis-sidebar-membership-pages">';
    dynamic_sidebar( 'membership' );
    echo '</aside>';
}

// Remove default sidebars
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
unregister_sidebar( 'sidebar-alt' );
unregister_sidebar( 'sidebar-primary' );

genesis();
