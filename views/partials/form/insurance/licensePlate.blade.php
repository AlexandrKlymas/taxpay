<form id="license_plate_form" onsubmit="getLicensePlateInfo(this);return false;">
    <div class="form-group @if(!empty($form['errors']['license_plate'])){{'has-error'}}@endif">
        <label for="license_plate">Державний номер ТЗ</label>
        <input style="text-transform:uppercase" id="license_plate" name="license_plate" type="text" placeholder="АА1111ВВ" required
               value="{{ $form['license_plate']??'' }}">
    </div>
    @if(!empty($form['errors']['license_plate']))
        <span class="help-block form-error">{{$form['errors']['license_plate'][0]}}</span>
    @endif
    <p>Натискаючи кнопку "Продовжити" Ви погоджуєтеся з умовами <a href="{{ UrlProcessor::makeUrl(7) }}" target="_blank" >публічного договору</a>.</p>

    <div class="form-footer">
        <input type="submit" class="btn" value="Продовжити">
    </div>
</form>