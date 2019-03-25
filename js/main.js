jQuery(document).ready(function($){

    $('.lever').on('click', '.lever-job-title', function() {
        if( $(this).closest('.lever-job').hasClass('active') ) {
            $('.lever-job').removeClass('active');
        } else {
            $('.lever-job').removeClass('active');
            $(this).closest('.lever-job').addClass('active');
        }
    });

});