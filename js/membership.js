(function($){
    $(document).ready(function(){
        $('form#membership-signup').on('submit', function(){
            __gaTracker('send', 'event', { eventCategory: 'Membership', eventAction: 'Sign Up', eventLabel: 'Join Membership', eventValue: 1});
        });
    });
})(jQuery);
