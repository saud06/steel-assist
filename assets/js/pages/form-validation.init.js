$(document).ready(function() {
    $('.parsley-examples').parsley();

    $('.add-user-form').parsley().on('field:validated', function(){
        var e = 0 === $('.parsley-error').length;

        $('.add-user-success').toggleClass('d-none', !e);
        $('.add-user-danger').toggleClass('d-none', e);
    }).on('form:submit', function(){
        // return false;
    });

    $('.update-user-form').parsley().on('field:validated', function(){
        var e = 0 === $('.parsley-error').length;

        $('.update-user-success').toggleClass('d-none', !e);
        $('.update-user-danger').toggleClass('d-none', e);
    }).on('form:submit', function(){
        // return false;
    });
});