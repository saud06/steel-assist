$(document).ready(function(){
    $('.parsley-examples').parsley();

    $('.login-form').parsley().on('field:validated', function(){
        var e = 0 === $('.parsley-error').length;

        $('.login-success').toggleClass('d-none', !e);
        $('.login-danger').toggleClass('d-none', e);
    }).on('form:submit', function(){
        // return false;
    });
});