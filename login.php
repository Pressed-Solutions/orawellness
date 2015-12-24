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
}

genesis();
