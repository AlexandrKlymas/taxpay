@php
    /** @var $services \EvolutionCMS\Main\Services\GovPay\Models\Service[]  */
@endphp
<label for="period_to">{{ $title }}:</label>
<select {!! $validationAttributes[$name] !!} name="{{ $name }}" id="{{ $name }}_field">

    <option data-price="0" value="">{{ $placeholder }}</option>


    @foreach($services as $service)
        <option data-price="{{ $service->price }}" value="{{  $service->id }}">
            {{ $service->name_ua }}
            @if($showPrice)
                ({{ number_format($service->price,2) }} грн)
            @endif
        </option>
    @endforeach

</select>
