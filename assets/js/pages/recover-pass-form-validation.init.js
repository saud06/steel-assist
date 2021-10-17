$(document).ready(function(){
    $('.parsley-examples').parsley();

    $('.recover-pass-form').parsley().on('field:validated', function(){
        var e = 0 === $('.parsley-error').length;

        $('.recover-pass-success').toggleClass('d-none', !e);
        $('.recover-pass-danger').toggleClass('d-none', e);
    }).on('form:submit', function(){
        // return false;
    });
});