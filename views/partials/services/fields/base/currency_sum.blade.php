<label for="sum">@if(!empty($title)){{ $title }}:@endif</label>
<div class="input-group">

    <input {!! $validationAttributes[$name] !!} id="{{ $name }}" name="{{ $name }}"
           class="js-sum-field"
           type="@if(!empty($hidden)){{'hidden'}}@else{{'text'}}@endif"
           @if(!empty($value)) value="{{$value}}" @endif
           @if(!empty($disabled)) style="pointer-events:none;" @endif
           placeholder="{{ $placeholder }}">
    <select name="currency" class="custom-select">
        @foreach($currencies as $currency)
            <option @if($loop->first){{'selected'}}@endif
            value="{{$currency}}">{{$currency}}</option>
        @endforeach
    </select>
</div>
