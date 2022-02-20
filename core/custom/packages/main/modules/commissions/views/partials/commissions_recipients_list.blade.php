<h3>Отримувачі комісій</h3>
<div class="container">
    <div class="row">
        @foreach($_commissions_recipients as $commission_recipient)
            <div class="col-4">
                <form class="border border-primary p-3" id="edit_commissions_recipients_form-{{$commission_recipient['id']}}" onsubmit="edit_commissions_recipients({{$commission_recipient['id']}});return false;">
                    <input type="hidden" name="id" value="{{$commission_recipient['id']}}">
                    <div class="form-group">
                        <label for="edit_commissions_recipients_form-{{$commission_recipient['id']}}-recipient_name">
                            Назва отримувача</label>
                        <input id="edit_commissions_recipients_form-{{$commission_recipient['id']}}-recipient_name"
                               value="{{$commission_recipient['recipient_name']}}"
                               type="text" name="recipient_name">
                    </div>
                    <div class="form-group">
                        <label for="edit_commissions_recipients_form-{{$commission_recipient['id']}}-edrpou">
                            ЄДРПОУ</label>
                        <input id="edit_commissions_recipients_form-{{$commission_recipient['id']}}-edrpou"
                               value="{{$commission_recipient['edrpou']}}"
                               type="text" name="edrpou">
                    </div>

                    <div class="form-group">
                        <label for="edit_commissions_recipients_form-{{$commission_recipient['id']}}-mfo">
                            МФО</label>
                        <input id="edit_commissions_recipients_form-{{$commission_recipient['id']}}-mfo"
                               value="{{$commission_recipient['mfo']}}"
                               type="text" name="mfo">
                    </div>

                    <div class="form-group">
                        <label for="edit_commissions_recipients_form-{{$commission_recipient['id']}}-iban">
                            iBAN</label>
                        <input id="edit_commissions_recipients_form-{{$commission_recipient['id']}}-iban"
                               value="{{$commission_recipient['iban']}}"
                               type="text" name="iban">
                    </div>

                    <div class="form-group">
                        <label for="edit_commissions_recipients_form-{{$commission_recipient['id']}}-purpose_template">
                            Шаблон призначення</label>
                        <input id="edit_commissions_recipients_form-{{$commission_recipient['id']}}-purpose_template"
                               value="{{$commission_recipient['purpose_template']}}"
                               type="text" name="purpose_template">
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <button class="btn btn-success mt-3 w-100" type="submit"><i class="fa fa-edit"></i> Зберегти зміни</button>
                        </div>
                        <div class="col">
                            <button class="btn btn-danger mt-3 w-100" type="button" onclick="delete_commission_recipient({{$commission_recipient['id']}})">
                                <i class="fa fa-remove"></i> Видалити комісію
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        @endforeach
    </div>

</div>
