<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

    // Show Ora Wellness logo
    echo '<img class="logo" src="' . get_stylesheet_directory_uri() . '/images/orawellness-logo.png" alt="Ora Wellness" />';

wc_print_notices(); ?>

<section class="reset-password">
    <h2>Reset Password</h2>
    <p>Lost your password? Please enter your username or email address. You will receive a reminder via email.</p>
    <p><a href="<?php echo home_url( '/my-account/' ); ?>">Log in here</a>.</p>
    <?php echo do_shortcode( '[memb_send_password]' ); ?>
</section>
