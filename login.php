<?php
/*
 * Template Name: Login/Logout Page
 */

//* If not logged in, remove all menus, header, sidebar, footer
if ( ! is_user_logged_in() ) {
    remove_action( 'genesis_header', 'genesis_do_header' );
    remove_action( 'genesis_after_header', 'genesis_do_subnav' );
    remove_action( 'genesis_before_header', 'ora_add_tertiary_menu' );
    remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
    remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
    remove_theme_support( 'genesis-footer-widgets', 3 );
} else {
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
}

genesis();
