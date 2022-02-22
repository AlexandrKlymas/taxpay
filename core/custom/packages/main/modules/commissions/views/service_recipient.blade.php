@extends('layout')

@section('content')

    <h2>{{$service_recipient['recipient_name']}}</h2>

    <div class="form-recipient mt-5">
        <h3>Данні платежу</h3>
        <div class="col-6">
            <form class="border border-primary p-3" id="edit_service_recipient" onsubmit="edit_service_recipient();return false;">
                <input type="hidden" name="id" value="{{$service_recipient['id']}}">
                <div class="form-group">
                    <label for="edit_service_recipient-recipient_name">Назва отримувача</label>
                    <input id="edit_service_recipient-recipient_name" type="text" name="recipient_name"
                           value="{{$service_recipient['recipient_name']}}">
                </div>
                <div class="form-group">
                    <label for="edit_service_recipient-edrpou">ЄДРПОУ</label>
                    <input id="edit_service_recipient-edrpou" type="text" name="edrpou"
                           value="{{$service_recipient['edrpou']}}">
                </div>

                <div class="form-group">
                    <label for="edit_service_recipient-mfo">МФО</label>
                    <input id="edit_service_recipient-mfo" type="text" name="mfo"
                           value="{{$service_recipient['mfo']}}">
                </div>

                <div class="form-group">
                    <label for="edit_service_recipient-iban">iBAN</label>
                    <input id="edit_service_recipient-iban" type="text" name="iban"
                           value="{{$service_recipient['iban']}}">
                </div>

                <div class="form-group">
                    <label for="edit_service_recipient-purpose_template">Шаблон призначення</label>
                    <input id="edit_service_recipient-purpose_template" type="text" name="purpose_template"
                           value="{{$service_recipient['purpose_template']}}">
                </div>

                <div class="form-group">
                    <label for="edit_service_recipient-sum">Сумма (буз комісій)</label>
                    <input id="edit_service_recipient-sum" type="text" name="sum"
                           value="{{$service_recipient['sum']}}">
                </div>

                <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i>Зберегти зміни</button>
            </form>
        </div>
    </div>

    <div class="mt-5">
        <h3>Додані комісії до основного платежу</h3>
        <div class="row">
            @foreach($service_commissions as $service_commission)
                <div class="col-4">
                    <form class="border border-primary p-3" onsubmit="edit_service_recipient_commission({{$service_commission['id']}});return false;"
                          id="edit-commission-form-percent-{{$service_commission['id']}}-form">
                        <input type="hidden" name="id" value="{{$service_commission['id']}}">
                        <div class="form-row">
                            <div class="col">
                                <label for="edit-commission-form-recipient_name-{{$service_commission['id']}}">Назва</label>
                                <input id="edit-commission-form-recipient_name-{{$service_commission['id']}}"
                                       value="{{$commissions_recipients[$service_commission['commissions_recipient_id']]['recipient_name']}}"
                                       placeholder="0.00" type="text" name="recipient_name">
                            </div>
                            <div class="col">
                                <label for="edit-commission-form-purpose-{{$service_commission['id']}}">Призначення</label>
                                <input id="edit-commission-form-purpose-{{$service_commission['id']}}"
                                       value="{{$commissions_recipients[$service_commission['commissions_recipient_id']]['purpose_template']}}"
                                       placeholder="0.00" type="text" name="purpose">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <label for="edit-commission-form-percent-{{$service_commission['id']}}">Процент</label>
                                <input id="edit-commission-form-percent-{{$service_commission['id']}}"
                                       value="{{$service_commission['percent']}}"
                                       placeholder="0.00" type="text" name="percent">
                            </div>
                            <div class="col">
                                <label for="edit-commission-form-min-{{$service_commission['id']}}">Мин</label>
                                <input id="edit-commission-form-min-{{$service_commission['id']}}"
                                       value="{{$service_commission['min']}}"
                                       placeholder="0.00" type="text" name="min">
                            </div>
                            <div class="col">
                                <label for="edit-commission-form-max-{{$service_commission['id']}}">Макс</label>
                                <input id="edit-commission-form-max-{{$service_commission['id']}}"
                                       value="{{$service_commission['max']}}"
                                       placeholder="0.00" type="text" name="max">
                            </div>
                            <div class="col">
                                <label for="edit-commission-form-fix-{{$service_commission['id']}}">Фикс</label>
                                <input id="edit-commission-form-fix-{{$service_commission['id']}}"
                                       value="{{$service_commission['fix']}}"
                                       placeholder="0.00" type="text" name="fix">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <button class="btn btn-success mt-3 w-100" type="submit"><i class="fa fa-edit"></i> Зберегти зміни</button>
                            </div>
                            <div class="col">
                                <button class="btn btn-danger mt-3 w-100"
                                        onclick="delete_service_recipient_commission({{$service_commission['id']}})"
                                        type="button"><i class="fa fa-remove"></i> Видалити комісію</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <div class="add-commission-form mt-5">
        <h3>Додати нову комісію до основного платежу</h3>
        <div class="col-6">
            <form class="border border-primary p-3" id="add_service_recipient_commission_form" onsubmit="add_service_recipient_commission();return false;">
                <input type="hidden" name="service_recipient_id" value="{{$service_recipient['id']}}">
                <label>Отримувачі</label>
                @foreach($commissions_recipients as $commissions_recipient)
                    <div class="form-check">
                        <input id="add-commission-form-{{$loop->iteration}}" class="form-check-input"
                               type="radio" name="commissions_recipient_id"
                               @if($loop->first) checked @endif
                               value="{{$commissions_recipient['id']}}">
                        <label class="form-check-label" for="add-commission-form-{{$loop->iteration}}">
                            {{$commissions_recipient['recipient_name']}} ({{mb_substr($commissions_recipient['purpose_template'],0,40)}}...)
                        </label>
                    </div>
                @endforeach

                <div class="form-row">
                    <div class="col">
                        <label for="add-commission-form-percent">Процент</label>
                        <input id="add-commission-form-percent" placeholder="0.00" type="text" name="percent">
                    </div>
                    <div class="col">
                        <label for="add-commission-form-min">Мин</label>
                        <input id="add-commission-form-min" placeholder="0.00" type="text" name="min">
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <label for="add-commission-form-max">Макс</label>
                        <input id="add-commission-form-max" placeholder="0.00" type="text" name="max">
                    </div>
                    <div class="col">
                        <label for="add-commission-form-fix">Фикс</label>
                        <input id="add-commission-form-fix" placeholder="0.00" type="text" name="fix">
                    </div>
                </div>

                <button class="btn btn-success" type="submit">Додати комісію</button>
            </form>
        </div>
    </div>

@endsection
