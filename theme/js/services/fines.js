$(document)
    .on('click', '#fines-use-search-form', function (e) {
        $('#fine-search-form-wrap').show()
        $('#service-steps').hide()
    })
    .on('click', '#fines-search-again', function (e) {
        $('#fine-search-form-wrap').show()
        $('#service-steps').hide()
    })
    .on('click', '.js-fine-pay', function (e) {
        $('#service_id').val(47);
        $('#fine_id').val($(this).data('id'));
        $('#service-form').submit()
    })
;




$(document).on('submit','#fines-search-form',function (e) {
    e.preventDefault();

    var $form = $(this);

    var $errorBlockType = $('#option-errors');
    var $errorBlock = $('#fines-search-errors');

    $errorBlockType.addClass('hide');
    $errorBlock.html('').hide();

    var data = $form.serializeArray();
    var values = {};
    for(var i =0; i < data.length;i++){
        values[data[i]['name']] = data[i]['value'];
    }

    var type = null;



    if(values['tax_number']){
        type = 'taxNumber';
    }
    else if(values['tech_passport']){
        type = 'techPassport';
    }
    else if(values['driving_license'] || values['driving_license_date_issue']){
        type = 'drivingLicense';
    }
    else if(values['fine_series'] || values['fine_number']){
        type = 'fine';
    }



    if(type === null){
        $errorBlockType.removeClass('hide');
        return;
    }


    $.ajax({
        type: 'POST',
        'url': '/services/fines/searchFines',
        data: $form.serialize(),


    }).done(function(response) {


        if(response.status){
            if(response.fines){
                $('#service-steps').show().html(response.fines);
                $('#fine-search-form-wrap').hide();
            }
           else{
                var $error = $('<div></div>').addClass('alert alert-danger').text(response.error);
                $errorBlock.append($error);
                $errorBlock.show()
            }


        }
        else {
            alert(response.error)

        }
    })

})