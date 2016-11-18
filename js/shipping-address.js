(function($){
    $(document).ready(function(){
        $('#copy-billing-to-shipping').on('click', function(){
            // loop through shipping fields and get matching billing info
            $('.shipping_address input:not([class*="select2"]), .shipping_address select').each(function(){
                var shippingName = $(this).attr('name'),
                    billingName = shippingName.replace('shipping', 'billing'),
                    billingValue = $('[name="'+billingName+'"]').val();
console.log($(this));
                // trigger onchange event to update validation
                $(this).val(billingValue).trigger('change');
            });
        });
    });
})(jQuery);
