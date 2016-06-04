jQuery(document).ready(function($){
    // on pageload
    $('.woocommerce-tabs #tab-faq .kb-content').slideUp();

    // on click
    $('.woocommerce-tabs #tab-faq').on('click', '.kb-header', function(){
        event.preventDefault();
        $(this).parent().next('.kb-content').slideToggle();
    });
});
