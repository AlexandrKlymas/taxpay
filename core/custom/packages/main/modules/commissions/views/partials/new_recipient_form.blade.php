<h3>Додати основний платіж</h3>
<div class="col-6">
    <form class="border border-primary p-3" id="add_service_recipient_form" onsubmit="add_service_recipient();return false;">
        <input type="hidden" name="service_id" value="{{$_service['id']}}">
        @if(!empty($_sub_service)) <input type="hidden" name="sub_service_id" value="{{$_sub_service['id']??''}}"> @endif
        <div class="form-group">
            <label for="add_service_recipient_form-recipient_name">Назва отримувача</label>
            <input id="recipient_name" type="text" name="recipient_name">
        </div>
        <div class="form-group">
            <label for="add_service_recipient_form-edrpou">ЄДРПОУ</label>
            <input id="add_service_recipient_form-edrpou" type="text" name="edrpou">
        </div>

        <div class="form-group">
            <label for="add_service_recipient_form-mfo">МФО</label>
            <input id="add_service_recipient_form-mfo" type="text" name="mfo">
        </div>

        <div class="form-group">
            <label for="add_service_recipient_form-iban">iBAN</label>
            <input id="add_service_recipient_form-iban" type="text" name="iban">
        </div>

        <div class="form-group">
            <label for="add_service_recipient_form-purpose_template">Шаблон призначення</label>
            <input id="add_service_recipient_form-purpose_template" type="text" name="purpose_template">
        </div>

        <div class="form-group">
            <label for="add_service_recipient_form-sum">Сумма (буз комісій)</label>
            <input id="add_service_recipient_form-sum" type="text" name="sum">
        </div>

        <button type="submit" class="btn btn-success">Додати отримувача</button>
    </form>
</div>
