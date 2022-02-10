@php
    /** @var \EvolutionCMS\Main\Services\FinesSearcher\Models\Fine[] $fines */
@endphp
<div class="row payment-services">
    <div class="col-12">
        @forelse($fines as $fine)
            <div class="box box-form">
                <div class="row form-row ">
                    <div class="col-2 form-group"><span class="fine-status {{ 'fine-status-'.$fine->getStatus()}}">{{ $fine->getStatusTitle() }}</span></div>
                    <div class="col-10 form-group">
                        @if($fine->canBePaid())
                            <input value="Сплатити" type="button" class="btn fine-box-btn js-fine-pay" data-id="{{ $fine->id }}" >
                        @endif
                    </div>
                </div>
                <div class="row form-row ">
                    <div class="col-2 form-group">
                        <b>{{ $fine->getType() }}</b>
                    </div>
                    <div class="col-10 form-group">
                        <b>{{ $fine->data['sumPenalty'] }}</b> грн
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p><b>Серія постанови:</b></p>
                        <p class="fine-field-value">{{ $fine->data['nprotocol'] }}</p>
                    </div>
                    <div class="col-6">
                        <p><b>Номер постанови:</b></p>
                        <p class="fine-field-value">{{ $fine->data['sprotocol'] }}</p>
                    </div>
                </div>
                @if(isset($fine->data['fab']))
                <div class="row">
                    <div class="col-12">
                        <p><b>Що порушили:</b></p>
                        <p class="fine-field-value">{{ $fine->data['fab'] }}</p>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-6">
                        <p><b>Дата порушення:</b></p>
                        <p class="fine-field-value">{{ date('d-m-Y',strtotime($fine->data['dperpetration'])) }}</p>
                    </div>
                    <div class="col-6">
                        <p><b>Час порушеня:</b></p>
                        <p class="fine-field-value">{{ date('H:i:s',strtotime($fine->data['dperpetration'])) }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p><b>Де порушили:</b></p>
                        <p class="fine-field-value">{{ $fine->getCrimePlace() }}</p>
                    </div>

                </div>
                <div class="form-footer">
                    <div class="back-wrap">
                        <a id="fines-search-again" class="back">Шукати ще</a>
                    </div>
                </div>
            </div>
        @empty
            <p>Штрафів не знайдено</p>
        @endforelse
    </div>


</div>
