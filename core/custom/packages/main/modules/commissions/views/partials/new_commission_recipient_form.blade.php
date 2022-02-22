<form class="border border-primary p-3" id="add_service_commission_recipient_form" onsubmit="add_service_commission_recipient();return false;">
    <div class="form-group">
        <label for="add_service_commission_recipient_form-recipient_name">Отримувачь</label>
        <input id="add_service_commission_recipient_form-recipient_name" type="text" name="recipient_name">
    </div>

    <div class="form-group">
        <label for="add_service_commission_recipient_form-mfo">МФО</label>
        <input id="add_service_commission_recipient_form-mfo" type="text" name="mfo">
    </div>
    <div class="form-group">
        <label for="add_service_commission_recipient_form-edrpou">ЄДРПОУ</label>
        <input id="add_service_commission_recipient_form-edrpou" type="text" name="edrpou">
    </div>
    <div class="form-group">
        <label for="add_service_commission_recipient_form-iban">iBan</label>
        <input id="add_service_commission_recipient_form-iban" type="text" name="iban">
    </div>
    <div class="form-group">
        <label for="add_service_commission_recipient_form-purpose_template">Шаблон призначення</label>
        <input id="add_service_commission_recipient_form-purpose_template" type="text" name="purpose_template">
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="add_service_commission_recipient_form-recipient_type">
                Тип отримувача</label>
        </div>
        <select class="custom-select" name="recipient_type" id="add_service_commission_recipient_form-recipient_type">
            <option selected>Оберіть...</option>
            @foreach($recipient_types as $recipient_type)
                <option value="{{$recipient_type}}">{{$recipient_type}}</option>
            @endforeach
        </select>
    </div>

    <button class="btn btn-success" type="submit">Додати нового отримувача комісії</button>
</form>