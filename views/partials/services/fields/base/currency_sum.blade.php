<label for="sum">@if(!empty($title)){{ $title }}:@endif</label>

<div class="input-group">
    <input {!! $validationAttributes[$name] !!} id="{{ $name }}" name="{{ $name }}"
           class="js-sum-field form-control"
           type="@if(!empty($hidden)){{'hidden'}}@else{{'text'}}@endif"
           @if(!empty($value)) value="{{$value}}" @endif
           @if(!empty($disabled)) style="pointer-events:none;" @endif
           placeholder="{{ $placeholder }}">
    <select id="currency" name="currency" class="custom-select form-control">
        @foreach($currencies as $currency)
            <option @if($loop->first){{'selected'}}@endif
            value="{{$currency['alias']}}">{{$currency['caption']}}</option>
        @endforeach
    </select>
</div>
