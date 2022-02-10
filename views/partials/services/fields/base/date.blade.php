<label for="{{ $name }}">{{ $title }}:</label>
<input {!! $validationAttributes[$name] !!} id="{{ $name }}" name="{{ $name }}" data-range-single type="text" placeholder="{{ $placeholder }}">