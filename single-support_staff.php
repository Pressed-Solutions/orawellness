<?php
/**
* Description: Support Staff
*/

// Add thumbnail
add_action( 'genesis_entry_content', 'ora_show_thumbnail', 8 );
function ora_show_thumbnail() {
    if ( has_post_thumbnail() ) {
        the_post_thumbnail( 'tagged-content-thumb', array( 'class' => 'alignright', '' ) );
    }
}

// Remove post meta
add_filter( 'genesis_post_info', function() { return false; } );

// Add link to all staff
add_filter( 'the_content', 'ora_add_support_staff_link', 5 );
function ora_add_support_staff_link( $content ) {
    $support_staff_obj = get_post_type_object( 'support_staff' );
    return $content . '<p><a href="' . home_url( '/' . $support_staff_obj->rewrite['slug'] . '/' ) . '">Meet the rest of our team</a></p>';
}

genesis();
