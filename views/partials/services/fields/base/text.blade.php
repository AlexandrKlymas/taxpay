<label for="{{ $name }}">{{ $title }}</label>
<input {!! $validationAttributes[$name] !!} id="{{ $name }}_field" name="{{ $name }}"
       type="@if(!empty($hidden)){{'hidden'}}@else{{'text'}}@endif"
       @if(!empty($value)) value="{{$value}}" @endif
       @if(!empty($disabled)){{'disabled="disabled"'}}@endif
       placeholder="{{ $placeholder }}">
