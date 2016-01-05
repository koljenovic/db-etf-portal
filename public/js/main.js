$(document).ready(function() {
    $('#login-forma').submit(function (e) {
        polja = $(this).find(':input');
        request = {};
        polja.each(function() {
            request[this.name] = $(this).val();
        });
        $.post('/login/', request, function (data, status) {
            if(data != '') {
                location.reload();
            }
        });
        e.preventDefault();
    });
});