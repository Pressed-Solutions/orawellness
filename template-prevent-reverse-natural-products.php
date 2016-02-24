<?php
/**
* Template Name: Prevent & Reverse/Best Natural Template
* Description: Used as a page template for the Prevent & Reverse and The Best Natural… pages
*/

add_action( 'genesis_after_loop', 'ora_show_child_pages' );
function ora_show_child_pages() {
    $child_args = array(
        'post_parent'       => get_the_ID(),
        'post_type'         => 'any',
        'post_status'       => 'publish',
        'numberposts'       => -1,
    );

    $page_children = get_children( $child_args );

    if ( $page_children ) {
        echo '<h2 class="post-grid-header alternate">' . get_the_title() . '</h2><div class="post-grid">';
        foreach( $page_children as $child_page ) {
            $child_ID = $child_page->ID;

            echo '<article class="page type-page page-' . $child_ID . '" itemscope itemtype="http://schema.org/CreativeWork">';
            echo '<header class="entry-title"><h2 class="entry-title" itemprop="headline"><a href="' . get_permalink( $child_ID ) . '">' . get_the_title( $child_ID ) . '</a></h2></header>';
            echo '<div class="entry-content" itemprop="text"><a href="' . get_permalink( $child_ID ) . '" aria-hidden="true">' . get_the_post_thumbnail( $child_ID, 'tagged-content-thumb', array( 'class' => 'alignright post-image' ) ) . '</a>';
            echo pippin_excerpt_by_id( $child_ID );
            echo '</div></article>';
        }
        echo '</div>';
    }
}

genesis();
