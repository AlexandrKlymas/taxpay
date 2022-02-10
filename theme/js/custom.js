function formatDate(date) {
    let day = date.getDate();
    if(day<10){
        day = "0"+day;
    }
    let month = date.getMonth() + 1;
    if(month < 10){
        month = "0"+month;
    }

    let year = date.getFullYear();

    return day+"."+month+"."+year;
}
$(document).on('submit', '.ajax-form', function(e) {
    e.preventDefault();
    var target = this;
    if ($(this).data('target') != undefined) {
        target = $(this).data('target');
    }
    values = $(this).serializeArray();
    $(this).find('input[type="submit"]').attr('disabled', 'disabled');
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: $(this).data('action'),
        data: values,
        success: function(data) {
            $(target).replaceWith(data);
        }
    });
});

// initDataPickers();

$('#period_from').mask("99.99.9999");
$('#period_to').mask("99.99.9999");
$('#driving_license_date_issue').mask("99.99.9999");
