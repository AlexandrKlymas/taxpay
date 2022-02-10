@php
    /** @var $fine \EvolutionCMS\Main\Services\FinesSearcher\Models\Fine  */
@endphp
📋 Постанова <b>№{{ $fine->getSeries() }}{{ $fine->getNumber() }}</b> від {{ $fine->getFineDate()->format('d.m.y') }}
<i>{{ $fine->getDescription() }}</i>
Адміністративне стягнення: ₴ {{ number_format($fine->getSum(),'2') }} грн.