<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Ora Wellness' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.2.2' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add Accessibility support
add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu',  'search-form', 'skip-links', 'rems' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Don’t need the full CSS header output on the page
add_action( 'stylesheet_uri', 'ora_add_stylesheet', 10, 2 );
function ora_add_stylesheet() {
    return get_stylesheet_directory_uri() . '/orawellness.css';
}

//* Add Typekit fonts
add_action( 'genesis_after', 'ora_add_web_font_loader' );
function ora_add_web_font_loader() {
    echo '<script>
       WebFontConfig = {
          typekit: { id: \'agy8tbj\' }
       };

       (function(d) {
          var wf = d.createElement(\'script\'), s = d.scripts[0];
          wf.src = \'https://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js\';
          s.parentNode.insertBefore(wf, s);
       })(document);
    </script>' . "\n";
}
