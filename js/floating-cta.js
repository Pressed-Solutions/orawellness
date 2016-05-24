jQuery(document).ready(function($) {
    // check for cookie existence
    if ($.cookie('floating-cta') == 'declined') {
        $('body').addClass('floating-cta-declined');
    }

    // on click close button
    $('#floating-cta .close').on('click', function() {
        $.cookie('floating-cta', 'declined', { expires: 31 });
        console.info('Floating CTA declined');
        $('body').addClass('floating-cta-declined');
    });
});
