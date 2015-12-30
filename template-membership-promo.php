<?php
/**
* Template Name: Membership Promotion
* Description: Used as a page template for the membership promotional page
*/

// Add page subtitle and banner
add_filter( 'genesis_post_title_text', 'ora_tagged_content_subheading' );
function ora_tagged_content_subheading( $title ) {
    if ( get_field( 'page_subtitle' ) ) {
        $title = '<h1 class="entry-title">' . get_field( 'page_subtitle' ) . '</h1>';
    }

    $title .= '<style>.entry-header { background-image: url(\'' . get_field( 'header_image' ) . '\'); } </style>';

    return $title;
}

}

// Remove sidebar
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );
genesis_unregister_layout( 'content-sidebar' );

genesis();
