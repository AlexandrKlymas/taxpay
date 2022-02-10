@extends('layout.default')

@php
    /** @var $units \EvolutionCMS\Models\SiteContent[] */
    /** @var $service \EvolutionCMS\Models\SiteContent */
@endphp
@section('content')
    <div class="wrap-spin">
        <div class="loader-spin">Loading...</div>
    </div>

    <h1>{{ $documentObject['pagetitle'] }}</h1>

    <div id="service-steps">
        <div class="row payment-services">
            <div class="col payment-services-left">

                <div class="box box-form">
                    @if(!empty($error))
                        <div class="service-error">
                            {{ $error }}
                        </div>
                    @endif
                    <div class="row form-row" style="display: none" data-title-ua>
                        <div class="col-12 form-group" style="text-align: center">
                            <h6>Дані заповнюються українською мовою</h6>
                        </div>
                    </div>
                    <div class="row form-row" data-switcher>
                        <div class="col-6 form-group" id="car-info-switcher" onclick="toggleForm('car_info')">
                            <h6 class="type-btn">Дані ТЗ</h6>
                        </div>
                        <div class="col-6 form-group" id="insurance-info-switcher" style="display: none;" onclick="toggleForm('insurance_info')">
                            <h6 class="type-btn">Дані страхувальника</h6>
                        </div>
                    </div>
                    <div id="car_info" data-formtoggler>
                        @if($form)
                            {!! $form !!}
                        @endif
                    </div>
                    <div id="insurance_info" data-formtoggler style="display: none"></div>
                    <div id="payment" data-formtoggler style="display: none"></div>
                </div>
                <div class="text-wrap" id="left-text">{!! $content_builder[0]['text'] !!}</div>
            </div>
            <div class="col payment-services-right">
                <div class="text-wrap">{!! $documentObject['content'] !!}</div>
            </div>
        </div>
    </div>
    <div id="service-preview" style="display: none">

    </div>

@endsection



@push('js')
    <script src="/theme/js/carInsurance.js?v={{filemtime('theme/js/carInsurance.js')}}"></script>
@endpush

@push('modals')
    <div class="modal fade" id="franchiseModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-lg modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <p class="modal-title">Виберіть франшизу</p>
                    <p class="desc">Це сума, яка не відшкодовується страховою компанією</p>
                </div>
                <div class="modal-body">
                    Якщо ви вибираєте франшизу в розмірі 1 000 грн, а завдано збитків на 700 грн, то відновлювати машину ви будете за свій рахунок.
                </div>
                <div style="display: flex; justify-content: center;">
                    <div class="form_group">
                        <button class="btn" type="button" data-dismiss="modal" onclick="setFranchise('0')">0 грн</button>
                        <div style="flex-direction: column; display: flex;align-items: center;">
                            вартість ОСЦПВ
                            <b>дорожче</b>
                        </div>
                    </div>
                    <div class="form_group">
                        <button class="btn" type="button" data-dismiss="modal" onclick="setFranchise('1500')">1500 грн</button>
                        <div style="flex-direction: column; display: flex;align-items: center;">
                            вартість ОСЦПВ
                            <b>дешевше</b>
                        </div>
                    </div>
                    <div class="form_group">
                        <button class="btn" type="button" data-dismiss="modal" onclick="setFranchise('2500')">2500 грн</button>
                        <div style="flex-direction: column; display: flex;align-items: center;">
                            вартість ОСЦПВ
                            <b>ще дешевше</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush
