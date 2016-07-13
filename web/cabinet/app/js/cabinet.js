$('document').ready(function() {


    $("#zoom").on("click", function() {
        $('#preview').attr('src', $('#image').attr('src'));
        $('#modal').modal('show');
    });
});
