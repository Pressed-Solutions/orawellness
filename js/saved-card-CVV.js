( function( $ ) {
    var target = '.saved-card-CVV';

    $( 'body' ).on( 'change', 'input.woocommerce-SavedPaymentMethods-tokenInput', function() {
        if ( $( this ).val() !== 'new' ) {
            $( target ).show();
            $( target + ' input[name="infusionsoft-card-cvc"]' ).attr( 'required', true );
            $( '#wc-infusionsoft-cc-form input' ).attr( 'disabled', true );
        } else {
            $( target ).hide();
            $( target + ' input[name="infusionsoft-card-cvc"]' ).attr( 'required' , null );
        }
    });
})( jQuery );
