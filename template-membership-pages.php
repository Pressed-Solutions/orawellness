<?php
/**
* Template Name: Membership Pages
* Description: Used as a page template for all the membership pages and subpages
*/

// Add body class for main membership page
global $post;
if ( get_post( $post )->post_name == 'membership-home' ) {
    add_filter( 'body_class', 'ora_membership_home_body_class' );
}
function ora_membership_home_body_class( $classes ) {
    $classes[] = 'membership-home';
    return $classes;
}

// Add page subtitle and banner
add_filter( 'genesis_post_title_text', 'ora_tagged_content_subheading' );
function ora_tagged_content_subheading( $title ) {
    if ( get_field( 'page_subtitle' ) ) {
        $title = '<h1 class="entry-title">' . wptexturize( get_field( 'page_subtitle' ) ) . '</h1>';
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
