<?php
/*
 * Template Name: Login/Logout Page
 */

//* Remove default page content
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

//* Add custom login form to page content
function ora_do_login_form() {
    $loggedin = is_user_logged_in();
    $user = wp_get_current_user();
    if ( $loggedin ) { ?>

        <h3>You are already logged in!</h3>
        <p>Hello, <?php echo $user->user_firstname; ?>! Looks like you are already signed in. Thanks for being a part of this website!</p>
        <p><a href="/">Go to Homepage</a> or <a href="<?php echo wp_logout_url( get_permalink() ); ?>">Log Out</a></p>

    <?php
    } else {
        $args = array(
            'form_id'			=> 'loginform',
            'redirect'			=> get_bloginfo( 'url' ),
            'id_username'		=> 'user_login',
            'id_password'		=> 'user_pass',
            'id_remember'		=> 'rememberme',
            'id_submit'			=> 'wp-submit',
            'label_username'	=> __( 'Username' ),
            'label_password'	=> __( 'Password' ),
            'label_remember'	=> __( 'Remember Me' ),
            'label_log_in'		=> __( 'Log In' ),
        );
        wp_login_form( $args );
    }
}
add_action( 'genesis_entry_content', 'ora_do_login_form' );

genesis();
