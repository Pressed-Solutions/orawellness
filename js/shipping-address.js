(function($){
    $(document).ready(function(){
        // copy billing info to shipping info
        $('#copy-billing-to-shipping').on('click', function(){
            // uncheck checkbox
            $('#ship-to-different-address-checkbox').removeAttr('checked');

            // loop through shipping fields and get matching billing info
            $('.shipping_address input:not([class*="select2"]), .shipping_address select').each(function(){
                var shippingName = $(this).attr('name'),
                    billingName = shippingName.replace('shipping', 'billing'),
                    billingValue = $('[name="'+billingName+'"]').val();

                // trigger onchange event to update validation
                $(this).val(billingValue).trigger('change');
            });
        });

        // watch shipping fields for changes and unset checkbox if they differ from billing address
        $('.shipping_address input:not([class*="select2"]), .shipping_address select').on('blur', function(){
            var shippingName = $(this).attr('name'),
                billingName = shippingName.replace('shipping', 'billing'),
                shippingValue = $(this).val(),
                billingValue = $('[name="'+billingName+'"]').val();

            // check checkbox if they differ
            if (shippingValue != billingValue) {
                $('#ship-to-different-address-checkbox').attr('checked', true);
            }
        });
    });
})(jQuery);
