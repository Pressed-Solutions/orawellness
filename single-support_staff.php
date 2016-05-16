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

genesis();
