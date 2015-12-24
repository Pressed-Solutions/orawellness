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
        echo '<a href="' . get_home_url() . '"><img class="logo" src="' . get_stylesheet_directory_uri() . '/images/logo.svg" alt="Ora Wellness" /></a>
        <section class="login">
        <h1>Log in to your account</h1>
        <form name="loginform" class="clearfix" id="loginform" action="' . esc_url( wp_login_url() ) . '" method="post">
            <p class="login-username">
                    <label for="user_login">Username</label>
                    <input type="text" name="log" id="use_login" class="input" size="20" />
            </p>
            <p class="login-password">
                    <label for="user_pass">Password</label>
                    <a href="' . wp_lostpassword_url() .'" class="forgot-password">Forgot Password?</a>
                    <input type="password" name="pwd" id="user_pass" class="input" size="20" />
            </p>
            <p class="login-remember">
                <label><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> Remember Me</label>
            </p>
            <p class="login-submit">
                <input type="submit" name="wp-submit" id="wp-submit" class="button-primary button light-blue clearfix" value="Log In" />
                <input type="hidden" name="redirect_to" value="/account/" />
            </p>
        </form>
        </section>';
    }
}
add_action( 'genesis_entry_content', 'ora_do_login_form' );

//* Remove all menus, header, sidebar, footer
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
remove_action( 'genesis_before_header', 'ora_add_tertiary_menu' );
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_theme_support( 'genesis-footer-widgets', 3 );

genesis();
