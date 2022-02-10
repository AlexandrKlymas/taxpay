function previewService(formData) {
    $.ajax({
        type: 'GET',
        url: 'service-preview',
        data: formData
    }).done(function (response) {
        if (response.status) {
            $('#service-preview').html(response.preview)
            $('#service-steps').hide();
            $('#service-preview').show();
        } else {
            alert('Сталась помилка зверніться до адміністратора')
        }
    });
}

$(document).on('submit', '.js-service-form', function (e) {
    var $form = $('#service-form');
    var $errors = $('#service-form-errors');

    var $formData = $form.serialize();
    $errors.html('');

    $.ajax({
        type: 'GET',
        url: 'service-validate',
        data: $formData
    }).done(function (response) {

        if (response.status) {
            previewService($formData)
        } else {
            var $owner = $('<div></div>').addClass('alert alert-danger');
            $errors.append($owner);
            for (var field in response.errors) {
                var errors = response.errors[field];

                for (var i = 0; i < errors.length; i++) {
                    $owner.append($('<div></div>').text(errors[i]))
                }
            }
        }
    });
    //отмена действия по умолчанию для кнопки submit
    e.preventDefault();
});

$(document).on('click', '#service-back', function () {
    $('#service-preview').html('')
    $('#service-steps').show();
    $('#service-preview').hide();
})

$(document).ajaxError(function myErrorHandler(event, xhr, ajaxOptions, thrownError) {
    alert('сталась помилка зверніться до адміністратора')
    console.log('fail');
});

function createServiceOrderAndPay(formData) {
    $.ajax({
        type: 'GET',
        'url': 'service-order-create-and-pay',
        data: formData,
    }).done(function (response) {


        var error = false;


        if (response.status) {

            if (response.redirectType === 'form') {
                var $paymentForm = $(response.paymentForm);
                $('body').append($paymentForm);
                $paymentForm.submit()
            } else if (response.redirectType === 'link') {
                window.location = response.redirectLink;
            } else {
                error = 'сталась помилка зверніться до адміністратора';
            }

        } else {
            error = 'сталась помилка зверніться до адміністратора';
        }

        if (error !== false) {
            alert(error);

            var $btn = $('#create-order-and-pay');

            if ($btn.length) {
                $btn.prop('disabled', false);
            }
        }

    })
}

$(document).on('click', '#create-order-and-pay', function () {
    var $form = $('#service-form');
    var formData = $form.serializeArray();

    $(this).prop('disabled', true);

    formData.push({
        name: 'payment_method',
        value: $('[name="payment_method"]:checked').val()
    })

    createServiceOrderAndPay(formData)

})

function redirectForm(url,data){
    let form = $('<form method="POST" action="' + url + '"></form>');
    data.forEach(function (input){
        form.append('<input type="hidden" name="'+input.name+'" value="'+input.value+'">');
    });
    $('body').append(form);
    form.submit();
}