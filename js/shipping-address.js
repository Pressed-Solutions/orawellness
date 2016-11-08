(function($){
    $(document).ready(function(){
        $('.woocommerce-shipping-fields').on('change', 'input#shipping-billing-addresses-checkbox', function(){
            if ($(this).attr('checked') == 'checked') {
                $('.shipping_address input:not([class*="select2"]), .shipping_address select').each(function(){
                    var shippingName = $(this).attr('name'),
                        billingName = shippingName.replace('shipping', 'billing'),
                        billingValue = $('[name="'+billingName+'"]').val();

                    $(this).val(billingValue).trigger('change');
                });
            } else {
                $('.shipping_address input:not([class*="select2"]), .shipping_address select').val(null).trigger('change');
            }
        });
    });
})(jQuery);
