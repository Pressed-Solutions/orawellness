( function( $ ) {
    var target = '.saved-card-CVV';

    $( 'body' ).on( 'change', 'input.woocommerce-SavedPaymentMethods-tokenInput', function() {
        if ( $( this ).val() !== 'new' ) {
            $( target ).show();
        } else {
            $( target ).hide();
        }
    });
})( jQuery );
