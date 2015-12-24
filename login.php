<?php
/*
 * Template Name: Login/Logout Page
 */

//* Remove default page content
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

//* Add custom login form to page content
function ora_do_login_form() {
    $loggedin = is_user_logged_in();

    echo '<a href="' . get_home_url() . '"><img class="logo" src="' . get_stylesheet_directory_uri() . '/images/logo.svg" alt="Ora Wellness" /></a>
    <section class="login">';

    if ( $_GET ) {
        if ( 'register' == $_GET['action'] ) {
            echo '<h1>Register for an account</h1>
            <p class="message register" id="reg_passmail">Registration confirmation will be emailed to you.</p>
            <form name="registerform" class="clearfix" id="registerform" action="' . wp_registration_url() . '" method="post" novalidate="novalidate">
            <p>
                <label for="user_login">Username<br>
                <input type="text" name="user_login" id="user_login" class="input" value="" size="20"></label>
            </p>
            <p>
                <label for="user_email">Email<br>
                <input type="email" name="user_email" id="user_email" class="input" value="" size="25"></label>
            </p>
            <input type="hidden" name="redirect_to" value="">
            <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary light-blue" value="Register"></p>
        </form>';
        } else if ( 'resetpassword' == $_GET['action'] ) {
            echo '<h1>Reset your password</h1>
            <p class="message reset-password">Enter your username or email below, then press &ldquo;Get New Password&rdquo; to receive a new password via email.</p>
            <form name="lostpasswordform" class="clearfix" id="lostpasswordform" action="' . wp_login_url() . '" method="post">
                <p>
                    <label for="user_login">Username or E-mail:<br>
                    <input type="text" name="user_login" id="user_login" class="input" value="" size="20"></label>
                </p>
                    <input type="hidden" name="redirect_to" value="">
                <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button-primary button light-blue" value="Get New Password"></p>
            </form>';
        }
    } else {
        echo '<h1>Log in to your account</h1>';

        // show logged-out message
        if ( $_GET && 'logout' == $_GET['action'] ) {
            echo '<p class="message logged-out">You have successfully logged out of your account.</p>';
        }

        echo '<form name="loginform" class="clearfix" id="loginform" action="' . site_url( '/wp-login.php' ) . '" method="post">
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
        </form>';
    }

    echo '</section>';
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
