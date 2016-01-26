<?php
/**
 * Description: The page used for the ebooks archive
 */

// add loop for testimonials
add_action( 'genesis_after_entry', 'ora_ebook_loop', 5 );
function ora_ebook_loop() {
    if ( get_query_var( 'paged' ) ) {
        $paged = get_query_var( 'paged' );
    } else {
        $paged = 1;
    }

    // WP_Query arguments
    $args = array (
        'post_type'              => array( 'ebook' ),
        'posts_per_page'         => '5',
        'pagination'             => true,
        'paged'                  => $paged,
        'orderby'                => 'post_name',
        'order'                  => 'ASC',
    );

    // The Query
    $ebook_query = new WP_Query( $args );

    // Search form
    echo '<form method="get" action="' . get_option( 'home' ) . '/" class="search-form" >
        <input type="search" value="'. $search_text .'" name="s" placeholder="Search the eBooks" />
        <input type="hidden" name="post_type" value="ebook" />
        <input type="submit" value="Search ebooks" />
    </form>
    ';

    // The Loop
    if ( $ebook_query->have_posts() ) {
        echo '<section class="custom-archive ebook">';
        while ( $ebook_query->have_posts() ) {
            $ebook_query->the_post();

            echo '<article class="ebook single clearfix">';
                // post thumbnail
                if ( has_post_thumbnail() ) {
                    echo '<a href="' . get_permalink() . '">' . get_the_post_thumbnail( get_the_ID(), array( 196, 160 ), array( 'class' => 'alignright' ) ) . '</a>';
                }

                // title
                echo '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
                // content
                echo '<p class="ebook-content">' . get_the_excerpt() . '</p>';
            echo '</article>';
        }
        custom_pagination( $ebook_query->max_num_pages, "", $paged );
        echo '</section><!-- .custom-archive.ebook -->';
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
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );
unregister_sidebar( 'sidebar-primary' );

genesis();
