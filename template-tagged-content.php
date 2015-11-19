<?php
/**
* Template Name: Tagged Content Template
* Description: Used as a page template for pages showing articles with specific tags
*/

// Add page subtitle
add_filter( 'genesis_post_title_text', 'ora_tagged_content_subheading' );
function ora_tagged_content_subheading( $title ) {
    if ( get_field( 'page_subtitle' ) ) {
        $title .= '<h2 class="entry-subtitle alternate">' . get_field( 'page_subtitle' ) . '</h2>';
    }

    return $title;
}

// Add featured image
add_action( 'genesis_entry_content', 'ora_show_thumbnail', 8 );
function ora_show_thumbnail() {
    if ( has_post_thumbnail() ) {
        the_post_thumbnail( 'tagged-content', array( 'class' => 'alignright' ) );
    }
}

genesis();
