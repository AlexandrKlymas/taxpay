<label for="sum">@if(!empty($title)){{ $title }}:@endif</label>
<div class="input-group">

    <input {!! $validationAttributes[$name] !!} id="{{ $name }}" name="{{ $name }}"
           class="js-sum-field"
           type="@if(!empty($hidden)){{'hidden'}}@else{{'text'}}@endif"
           @if(!empty($value)) value="{{$value}}" @endif
           @if(!empty($disabled)) style="pointer-events:none;" @endif
           placeholder="{{ $placeholder }}">
    @if(empty($hidden))
        <div class="input-group-text">
            грн
        </div>
    @endif
</div>
