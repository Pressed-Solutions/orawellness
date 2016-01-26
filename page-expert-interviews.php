<?php
/**
 * Description: The page used for the expert interviews archive
 */
echo '<h1>testing123</h1>';
// add loop for testimonials
add_action( 'genesis_after_entry', 'ora_expert_interview_loop', 5 );
function ora_expert_interview_loop() {
    if ( get_query_var( 'paged' ) ) {
        $paged = get_query_var( 'paged' );
    } else {
        $paged = 1;
    }

    // WP_Query arguments
    $args = array (
        'post_type'              => array( 'expert_interview' ),
        'posts_per_page'         => '5',
        'pagination'             => true,
        'paged'                  => $paged,
    );

    // The Query
    $expert_interview_query = new WP_Query( $args );

    // Search form
    echo '<form method="get" action="' . get_option( 'home' ) . '/" class="search-form" >
        <input type="search" value="'. $search_text .'" name="s" placeholder="Search expert interviews" />
        <input type="hidden" name="post_type" value="expert_interview" />
        <input type="submit" value="Search expert interviews" />
    </form>
    ';

    // The Loop
    if ( $expert_interview_query->have_posts() ) {
        echo '<section class="custom-archive expert-interview">';
        while ( $expert_interview_query->have_posts() ) {
            $expert_interview_query->the_post();

            echo '<article class="expert-interview single clearfix">';
                // post thumbnail
                if ( get_field( 'photo' ) ) {
                    echo '<a href="' . get_permalink() . '">' . wp_get_attachment_image( get_field( 'photo' ), array( 80, 80 ), true, array( 'class' => 'alignright avatar' ) ) . '</a>';
                }

                // title
                echo '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
                // content
                echo '<p class="expert-interview-content">' . get_the_excerpt() . '</p>';
            echo '</article>';
        }
        custom_pagination( $expert_interview_query->max_num_pages, "", $paged );
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
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );
unregister_sidebar( 'sidebar-primary' );

genesis();
