$(document).on('submit','#online-request-form',function(e) {
    var $form = $(this);
    var $errors = $('#request-form-errors');

    var $formData = $form.serialize();
    $errors.html('');

    $.ajax({
        type: 'POST',
        url: 'service-online-request-send-form',
        data: $formData
    }).done(function(response) {
        if(response.status){
            $('#online-request').html(response.message)
        }
        else{
            var $owner = $('<div></div>').addClass('alert alert-danger');
            $errors.append($owner);
            for(var field in response.errors){
                var errors = response.errors[field];

                for(var i = 0;i<errors.length;i++){
                    $owner.append($('<div></div>').text(errors[i]))
                }
            }
        }
    });
    //отмена действия по умолчанию для кнопки submit
    e.preventDefault();
});