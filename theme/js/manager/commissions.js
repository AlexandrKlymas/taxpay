
function add_sub_service() {
    let name = prompt('Задайте назву дочірнього сервісу', '');
    if (name !== null) {
        $.post(module_url+'&action=add_sub_service', {service_id: service_id, name: name})
            .done(function (data) {
                location.reload();
            });
    }
}

function edit_sub_service(id) {
    let name = $('[data-sub_service_id="' + id + '"]').html()
    let new_name = prompt('Задайте нову назву субсурвісу', name);
    if (new_name !== null) {
        $.post(module_url+'&action=edit_sub_service', {id: id, name: new_name})
            .done(function (data) {
                $('[data-sub_service_id="' + id + '"]').html(new_name);
            });
    }
}

function delete_sub_service(id) {
    let name = $('[data-sub_service_id="' + id + '"]').html()
    if (confirm('Ви насправді бажаєте видалити "' + name + '"?')) {
        $.post(module_url+'&action=delete_sub_service', {id: id})
            .done(function (data) {
                location.reload();
            });
    }
}

function add_service_recipient() {
    let $form = $('#add_service_recipient_form');
    $.post(module_url+'&action=add_service_recipient', $form.serialize())
        .done(function (data) {
            location.reload();
        });
}

function add_service_commission_recipient() {
    let $form = $('#add_service_commission_recipient_form');
    $.post(module_url+'&action=add_service_commission_recipient', $form.serialize())
        .done(function (data) {
            location.reload();
        });
}

function add_service_recipient_commission() {
    let $form = $('#add_service_recipient_commission_form');
    $.post(module_url+'&action=add_service_recipient_commission', $form.serialize())
        .done(function (data) {
            location.reload();
        });
}

function edit_service_recipient() {
    let $form = $('#edit_service_recipient');
    $.post(module_url+'&action=edit_service_recipient', $form.serialize())
        .done(function (data) {
            location.reload();
        });
}

function edit_service_commission(id,field,value) {
    let new_value = prompt('Задайте нове значення', value);
    if (new_value !== null) {
        $.post(module_url+'&action=edit_service_commission', {id: id, field:field, value: new_value})
            .done(function (data) {
                location.reload();
            });
    }
}

function delete_service_recipient(id) {
    let name = $('[data-service_recipient_id="' + id + '"]').html();
    if (confirm('Ви насправді бажаєте видалити "' + name + '"?')) {
        $.post(module_url+'&action=delete_service_recipient', {id: id})
            .done(function (data) {
                location.reload();
            });
    }
}

function edit_commissions_recipients(id){
    let $form = $('#edit_commissions_recipients_form-'+id);
    $.post(module_url+'&action=edit_service_recipient', $form.serialize())
        .done(function (data) {
            location.reload();
        });
}
function delete_commission_recipient(id){
    let name = $('#edit_commissions_recipients_form-'+id+'-recipient_name').val()
    if (confirm('Ви насправді бажаєте видалити "' + name + '"?')) {
        $.post(module_url+'&action=delete_commissions_recipients', {id: id})
            .done(function (data) {
                location.reload();
            });
    }
}
