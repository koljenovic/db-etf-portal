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

    $('#clanak-forma').submit(function (e) {
        polja = $(this).find(':input');
        request = {};
        polja.each(function() {
            request[this.name] = $(this).val();
        });
        request['tekst'] = CKEDITOR.instances.tekst.getData();
        $.post('/media/', request, function (data, status) {
            console.log(status);
            console.log(data.id);
            if (status == 'success') {
                $('#id').val(data.id);
                location.replace('/media/' + data.id + '/');
            }
            //location.reload();
        }, 'json');
        e.preventDefault();
    });
});