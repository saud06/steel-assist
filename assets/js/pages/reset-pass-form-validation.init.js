$(document).ready(function(){
    $('.parsley-examples').parsley();

    $('.reset-pass-form').parsley().on('field:validated', function(){
        var e = 0 === $('.parsley-error').length;

        $('.reset-pass-success').toggleClass('d-none', !e);
        $('.reset-pass-danger').toggleClass('d-none', e);
    }).on('form:submit', function(){
        // return false;
    });
});