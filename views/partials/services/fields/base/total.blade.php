<label for="sum">{{ $title }}:</label>
<div class="input-group">

    <input {!! $validationAttributes[$name] !!} id="{{ $name }}" name="{{ $name }}" disabled type="text" class="js-total-input" placeholder="{{ $placeholder }}">
    <div class="input-group-text">
        грн
    </div>
</div>
