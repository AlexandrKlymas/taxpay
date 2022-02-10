<label for="period_to">{{ $title }}:</label>
<select {!! $validationAttributes[$name] !!} name="{{ $name }}" id="{{ $name }}">
    <option value="">{{ $placeholder }}</option>
    @if(is_array($options))

        @foreach($options as $value => $caption)
        <option value="{{  $value }}">{{ $caption }}</option>
        @endforeach
    @endif
</select>
