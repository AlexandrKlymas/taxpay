<form data-user-info id="osago_form" onsubmit="getContract(this);return false;">
    <div class="form-group @if(!empty($form['errors']['fullname'])){{'has-error'}}@endif">
        <label for="fullname">Прізвище, ім`я та по-батькові</label>
        <input id="fullname" name="fullname" type="text" required
               placeholder="Петров Петро Петрович" value="{{$form['fullname']??''}}">
        @if(!empty($form['errors']['fullname']))
            <span class="help-block form-error">{{$form['errors']['fullname'][0]}}</span>
        @endif
    </div>
    <div class="row form-row">
        <div class="col-6 form-group @if(!empty($form['errors']['birthday'])){{'has-error'}}@endif">
            <label for="birthday">Дата народження</label>
            <input data-range-single id="birthday" name="birthday" type="text" required
                   placeholder="dd.mm.YYYY" value="{{$form['birthday']??''}}">
        </div>
        <div class="col-6 form-group @if(!empty($form['errors']['phone'])){{'has-error'}}@endif">
            <label for="phone">Телефон</label>
            <input class="input-phone" id="phone" name="phone" type="text" required
                   placeholder="+38(___)___-__-__" value="{{$form['phone']??''}}">
        </div>
        <div class="col-12 form-group">
            @if(!empty($form['errors']['birthday']))
                <span class="help-block form-error">{{$form['errors']['birthday'][0]}}</span>
            @endif
            @if(!empty($form['errors']['phone']))
                <span class="help-block form-error">{{$form['errors']['phone'][0]}}</span>
            @endif
        </div>
    </div>
    <div class="form-group @if(!empty($form['errors']['address'])){{'has-error'}}@endif">
        <label for="address">Адреса</label>
        <input id="address" name="address" type="text" required
               placeholder="Васильківська 55, кв. 77" value="{{$form['address']??''}}">
        @if(!empty($form['errors']['address']))
            <span class="help-block form-error">{{$form['errors']['address'][0]}}</span>
        @endif
    </div>
    <div class="row form-row">
        <div class="col-6 form-group @if(!empty($form['errors']['ipn'])){{'has-error'}}@endif">
            <label for="ipn">ІПН</label>
            <input id="ipn" name="ipn" type="text" maxlength="10" minlength="10" required
                   placeholder="012345678901" value="{{$form['ipn']??''}}">
        </div>
        <div class="col-6 form-group @if(!empty($form['errors']['email'])){{'has-error'}}@endif">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" required
                   placeholder="mail@example.com" value="{{$form['email']??''}}">
        </div>
        @if(!empty($form['errors']['ipn']))
            <span class="help-block form-error">{{$form['errors']['ipn'][0]}}</span>
        @endif
        @if(!empty($form['errors']['email']))
            <span class="help-block form-error">{{$form['errors']['email'][0]}}</span>
        @endif
    </div>
    <div class="row form-row">
        <div class="col-6 form-group @if(!empty($form['errors']['driving_licence'])){{'has-error'}}@endif">
            <label for="driving_licence">Посвідчення водія Серія та номер</label>
            <input style="text-transform:uppercase" id="driving_licence" name="driving_licence" type="text" required
                   placeholder="ААА 111111" value="{{$form['driving_licence']??''}}">
        </div>
        <div class="col-6 form-group @if(!empty($form['errors']['driving_licence_date'])){{'has-error'}}@endif">
            <label for="driving_licence_date">Дата видачі</label>
            <input data-range-single id="driving_licence_date" name="driving_licence_date" type="text" required
                   placeholder="dd.mm.YYYY" value="{{$form['driving_licence_date']??''}}">
        </div>
        <div class="col-12 form-group">
            @if(!empty($form['errors']['driving_licence']))
                <span class="help-block form-error">{{$form['errors']['driving_licence'][0]}}</span>
            @endif
            @if(!empty($form['errors']['driving_licence_date']))
                <span class="help-block form-error">{{$form['errors']['driving_licence_date'][0]}}</span>
            @endif
        </div>
    </div>
    <div class="form-group @if(!empty($form['errors']['issued_by'])){{'has-error'}}@endif">
        <label for="issued_by">Ким видано</label>
        <input style="text-transform:uppercase" id="issued_by" name="issued_by" type="text" required
               value="{{$form['issued_by']??''}}">
            @if(!empty($form['errors']['issued_by']))
                <span class="help-block form-error">{{$form['errors']['issued_by'][0]}}</span>
            @endif
    </div>
    <div class="row form-row">
        <div class="col-6 form-group @if(!empty($form['errors']['insurance_date'])){{'has-error'}}@endif">
            <label for="insurance_date">Дата початку дії полісу</label>
            <input data-range-single id="insurance_date" name="insurance_date" required
                   type="text" value="{{$form['insurance_date']??''}}">
        </div>
        <div class="col-6 form-group @if(!empty($form['errors']['sum'])){{'has-error'}}@endif">
            <label for="sum">Вартість страховки</label>
            <div class="input-group">
                <input id="sum" name="sum" required
                       type="text" value="{{$form['sum']??''}}" readonly="readonly">
                <div class="input-group-text">
                    грн
                </div>
            </div>
        </div>
        <div class="col-12 form-group">
            @if(!empty($form['errors']['insurance_date']))
                <span class="help-block form-error">{{$form['errors']['insurance_date'][0]}}</span>
            @endif
            @if(!empty($form['errors']['sum']))
                <span class="help-block form-error">{{$form['errors']['sum'][0]}}</span>
            @endif
        </div>
    </div>
    <p>Натискаючи кнопку "Продовжити" Ви погоджуєтеся з умовами <a href="{{ UrlProcessor::makeUrl(7) }}" target="_blank" >публічного договору</a>.</p>

    <div class="form-footer">
        <input type="submit" class="btn" value="Продовжити">
    </div>
</form>