$(document).ready(function(){
    $('.parsley-examples').parsley();

    $('.add-party-form').parsley().on('field:validated', function(){
        var e = 0 === $('.parsley-error').length;

        $('.add-party-success').toggleClass('d-none', !e);
        $('.add-party-danger').toggleClass('d-none', e);
    }).on('form:submit', function(){
        // return false;
    });

    $('.update-party-form').parsley().on('field:validated', function(){
        var e = 0 === $('.parsley-error').length;

        $('.update-party-success').toggleClass('d-none', !e);
        $('.update-party-danger').toggleClass('d-none', e);
    }).on('form:submit', function(){
        // return false;
    });
});