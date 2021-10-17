$(document).ready(function(){
    $('.parsley-examples').parsley();

    $('.add-parts-form').parsley().on('field:validated', function(){
        var e = 0 === $('.parsley-error').length;

        $('.add-parts-success').toggleClass('d-none', !e);
        $('.add-parts-danger').toggleClass('d-none', e);
    }).on('form:submit', function(){
        // return false;
    });

    $('.update-parts-form').parsley().on('field:validated', function(){
        var e = 0 === $('.parsley-error').length;

        $('.update-parts-success').toggleClass('d-none', !e);
        $('.update-parts-danger').toggleClass('d-none', e);
    }).on('form:submit', function(){
        // return false;
    });
});