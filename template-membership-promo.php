<?php
/**
* Template Name: Membership Promotion
* Description: Used as a page template for the membership promotional page
*/

// Add page subtitle and banner
add_filter( 'genesis_post_title_text', 'ora_tagged_content_subheading' );
function ora_tagged_content_subheading( $title ) {
    if ( get_field( 'page_subtitle' ) ) {
        $title = '<h1 class="entry-title">' . wptexturize( get_field( 'page_subtitle' ) ) . '</h1>';
    }

    $title .= '<style>.entry-header { background-image: url(\'' . get_field( 'header_image' ) . '\'); } </style>';

    return $title;
}

// Add signup form
add_action( 'genesis_entry_content', 'ora_membership_form', 5 );
function ora_membership_form() {
    if ( get_field( 'form_content' ) ) { ?>
    <div class="membership-signup-form">
    <?php echo do_shortcode( get_field( 'form_content' ) ) ?>
        <p class="note"><?php echo do_shortcode( get_field( 'form_note' ) ) ?></p>
    </div>
    <?php }
}

// Add footer
add_action( 'genesis_entry_content', 'ora_membership_footer', 15 );
function ora_membership_footer() {
    if ( get_field( 'footer_content' ) ) {
        echo '<div class="membership-promo-footer">' . do_shortcode( get_field( 'footer_content' ) ) . '</div>';
    }
    if ( get_field( 'footer_image' ) ) {
        echo '<style>.membership-promo-footer { background-image: url(\'' . get_field( 'footer_image' ) . '\'); } </style>';
    }
}

// Add testimonials
add_action( 'genesis_before_footer', 'ora_membership_footer_testimonials', 5 );
function ora_membership_footer_testimonials() {
    ora_show_testimonials( 3 );
}

// Remove sidebar
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
unregister_sidebar( 'sidebar-alt' );
genesis_unregister_layout( 'content-sidebar' );

genesis();
