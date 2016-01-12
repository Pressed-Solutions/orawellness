<?php
/**
* Template Name: Membership Pages
* Description: Used as a page template for all the membership pages and subpages
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

// Add membership sidebar
add_action( 'genesis_after_content', 'ora_show_membership_sidebar' );
function ora_show_membership_sidebar() {
    echo '<aside class="sidebar sidebar-membership widget-area" role="complementary" aria-label="Membership Pages Sidebar" itemscope itemtype="http://schema.org/WPSideBar" id="genesis-sidebar-membership-pages">';
    global $current_user;
    echo '<section id="member-info" class="widget member-info">
        <div class="widget-wrap">
            ' . get_avatar( $current_user->data->ID, 150 ) . '
            <h3>' . $current_user->data->display_name . '</h3>
            <p class="alternate">' . $current_user->user_email . '</p>
            <p><strong><a href="' . home_url( '/my-account/' ) . '">My Account</a></strong></p>
		</div>
    </section>';
    dynamic_sidebar( 'membership' );
    echo '</aside>';
}

// Remove default sidebars
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );
unregister_sidebar( 'sidebar-primary' );

genesis();
