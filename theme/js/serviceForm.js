


$(document).on('submit','.js-service-form',function(e) {
    var $form = $(this);
    var $errors = $('#service-form-errors');

    var $formData = $form.serialize();

    $errors.html('');



    $.ajax({
        type: 'GET',
        url: 'service-validate',
        data: $formData
    }).done(function(response) {
        if(response.status){

            $.ajax({
                type: 'GET',
                url: 'service-preview',
                data: $formData
            }).done(function(response) {
                if(response.status){
                    $('#service-preview').html(response.preview)
                    $('#service-steps').hide();
                    $('#service-preview').show();
                }
                else{
                    alert('Сталась помилка зверніться до адміністратора')
                }
            });


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
$(document).ajaxError(function myErrorHandler(event, xhr, ajaxOptions, thrownError) {
    alert('сталась помилка зверніться до адміністратора')
    console.log('fail');
});
