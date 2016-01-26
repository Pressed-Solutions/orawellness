<?php
/**
* Description: Expert Interviews
*/

// Add expert interview content
add_action( 'genesis_entry_content', 'ora_expert_interviews_content', 8 );
function ora_expert_interviews_content() {
    // author info
    echo '<section class="author-info">';
        echo wp_get_attachment_image( get_field( 'photo' ), array( 80, 80 ), true, array( 'class' => 'avatar' ) );
        if ( get_field( 'title' ) ) {
            echo '<h3 class="alternate">' . get_field( 'title' ) . '</h3>';
        }
        echo '<h2>' . get_field( 'name' ) . '</h2>';
        if ( get_field( 'biography' ) ) {
            echo wpautop( get_field( 'biography' ) );
        }
    echo '</section><!-- .author-info-->';

    // introduction
    the_field( 'introduction' );

    // episode summary
    if ( get_field( 'episode_summary' ) ) {
        echo '<p>In this episode, we cover:</p>';
        the_field( 'episode_summary' );
    }

    // links discussed
    if ( get_field( 'links_discussed' ) ) {
        echo '<h2>Links Discussed</h2>';
        the_field( 'links_discussed' );
    }

    // media
    echo '<h2>Listen</h2>';
    echo wp_oembed_get( esc_url( get_field( 'media_url' ) ) );

    // transcript
    echo '<h2>Transcript</h2>';

    // transcript PDF
    if ( get_field( 'transcript_pdf_link' ) ) {
        echo '<a class="transcript-link alignright" href=' . esc_url( get_field( 'transcript_pdf_link' ) ) . '" target="_blank">Download Transcript PDF</a>';
    }
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
