<form id="osago_form" onsubmit="getLicensePlateInfo(this);return false;">
    <input type="hidden" name="franchise" value="{{ $form['franchise']??0 }}" onchange="formChanged()">
    <input type="hidden" name="programId" value="@if(!empty($form['programId']) && !empty($form['program'])){{ $form['programId'] }}@else{{''}}@endif" onchange="formChanged()">
    <input type="hidden" name="sortBy" value="{{ $form['sortBy']??'fullprice' }}" onchange="formChanged()">
    <input type="hidden" name="sortOrder" value="{{ $form['sortOrder']??'asc' }}" onchange="formChanged()">
    <div class="form-group @if(!empty($form['errors']['license_plate'])){{'has-error'}}@endif">
        <label for="license_plate">Державний номер ТЗ</label>
        <input style="text-transform:uppercase" id="license_plate" name="license_plate" type="text" required
               placeholder="АА1111ВВ"
               value="{{ $form['license_plate']??'' }}">
        @if(!empty($form['errors']['license_plate']))
            <span class="help-block form-error">{{$form['errors']['license_plate'][0]}}</span>
        @endif
    </div>
    <div class="form-group @if(!empty($form['errors']['brand_model'])){{'has-error'}}@endif">
        <label for="brand_model">Марка і модель ТЗ</label>
        <input id="brand_model" name="brand_model" type="text" required
               placeholder="Марка і модель ТЗ"
               value="{{ $form['brand_model']??'' }}" list="brand-model-list">
        @if(!empty($form['errors']['brand_model']))
            <span class="help-block form-error">{{$form['errors']['brand_model'][0]}}</span>
        @endif
        <datalist id="brand-model-list">
            @foreach($form['cars'] as $car)
                <option value="{{$car}}">{{$car}}</option>
            @endforeach
        </datalist>
    </div>
    <div class="form-group @if(!empty($form['errors']['vin'])){{'has-error'}}@endif">
        <label for="vin">VIN code (номер шасі)</label>
        <input id="vin" name="vin" type="text" required
               placeholder="VIN code (номер шасі)"
               value="{{ $form['vin']??'' }}">
        @if(!empty($form['errors']['vin']))
            <span class="help-block form-error">{{$form['errors']['vin'][0]}}</span>
        @endif
    </div>
    <div class="form-group @if(!empty($form['errors']['carType'])){{'has-error'}}@endif">
        <label for="carType">Транспортний засіб</label>
        <select name="carType" id="carType" required>
            @if(!empty($form['carTypes']))
                <option disabled value="">Транспортний засіб</option>
                @foreach($form['carTypes'] as $category)
                    <option @if(!empty($form['carType']) && $form['carType']==$category['id']){{'selected'}}@endif
                            value="{{$category['id']}}">
                        {{$category['name']}}</option>
                @endforeach
            @endif
        </select>
        @if(!empty($form['errors']['carType']))
            <span class="help-block form-error">{{$form['errors']['carType'][0]}}</span>
        @endif
    </div>
    <div class="form-group @if(!empty($form['errors']['city'])){{'has-error'}}@endif">
        <label for="city">Місто прописки власника ТЗ (рекомендуємо уточнювати)</label>
        <input id="city" name="city" type="text" required
               value="{{ $form['city']??'' }}" list="city-list">
        <datalist id="city-list">
            @foreach($form['cityZones'] as $city)
                <option value="{{$city['city']}}">{{$city['city']}}</option>
            @endforeach
        </datalist>
        @if(!empty($form['errors']['city']))
            <span class="help-block form-error">{{$form['errors']['city'][0]}}</span>
        @endif
    </div>
    <div class="form-group @if(!empty($form['errors']['prodYear'])){{'has-error'}}@endif">
        <label for="prodYear">Рік випуску ТЗ (рекомендуємо уточнювати)</label>
        <input id="prodYear" name="prodYear" type="text" placeholder="YYYY" required
               maxlength="4" minlength="4"
               value="@if(!empty($form['prodYear'])){{ $form['prodYear'] }}@endif">
        @if(!empty($form['errors']['prodYear']))
            <span class="help-block form-error">{{$form['errors']['prodYear'][0]}}</span>
        @endif
    </div>
    <p>Натискаючи кнопку "Продовжити" Ви погоджуєтеся з умовами <a href="{{ UrlProcessor::makeUrl(7) }}" target="_blank" >публічного договору</a>.</p>

    <div class="form-footer">
        <input type="submit" class="btn" value="Продовжити">
    </div>
</form>