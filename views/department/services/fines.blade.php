@extends('layout.default')

@section('content')

    <h1>{{ $documentObject['pagetitle'] }}</h1>

    <div id="fine-search-form-wrap" {!!  $renderMode !=='searchForm'?'style="display:none;"':'' !!}>
        <div class="row payment-services">
            <div class="col payment-services-left">
                <div class="box box-form">
                    <form id="fines-search-form" data-validation-form="true" >
                        <div class="form-group">
                            <label for="license_plate">{{ $documentObject['field_car_number_title'] }}</label>
                            <input id="license_plate" data-validation="required" class="text-uppercase"  name="license_plate" type="text" placeholder="{{ $documentObject['field_car_number_placeholder'] }}" value="">
                        </div>
                        <p class="form-title">
                            Заповніть один із варіантів
                        </p>
                        <div id="option-errors" class="hide">
                            <div class="form-error">Ви не заповнили один із варіантів</div>
                        </div>

                        <div class="form-group">
                            <label for="tax_number">{{ $documentObject['field_tax_number_title'] }}</label>
                            <input id="tax_number" name="tax_number" type="text"  placeholder="{{ $documentObject['field_tax_number_placeholder'] }}">

                        </div>

                        <div class="form-group">
                            <label for="tech_passport">{{ $documentObject['field_tech_passport_title'] }}</label>
                            <input id="tech_passport" name="tech_passport" type="text" class="text-uppercase" placeholder="{{ $documentObject['field_tech_passport_placeholder'] }}">
                        </div>

                        <div class="row form-row form-row_custom">
                            <div class="col-8 form-group">
                                <label for="driving_license">{{ $documentObject['field_driving_license_title'] }}</label>
                                <input id="driving_license" name="driving_license" class="text-uppercase" type="text" placeholder="{{ $documentObject['field_driving_license_placeholder'] }}">
                            </div>
                            <div class="col-4 form-group">
                                <label for="driving_license_date_issue">{{ $documentObject['field_driving_license_date_issue_title'] }}</label>
                                <input id="driving_license_date_issue"
                                       data-validation="required"
                                       data-validation-depends-on="driving_license"
                                       data-range-single
                                       name="driving_license_date_issue"
                                       type="text"
                                       placeholder="{{ $documentObject['field_driving_license_date_issue_placeholder'] }}"
                                >
                            </div>
                        </div>


                        <div class="row form-row form-row_custom">
                            <div class="col-8    form-group">
                                <label for="fine_series">{{ $documentObject['field_fine_series_title'] }}</label>
                                <input id="fine_series" name="fine_series" class="text-uppercase" type="text" placeholder="{{ $documentObject['field_fine_series_placeholder'] }}" value="">
                            </div>
                            <div class="col-4 form-group">
                                <label for="fine_number">{{ $documentObject['field_fine_number_title'] }}</label>
                                <input id="fine_number"
                                       data-validation="required"
                                       data-validation-depends-on="fine_series"
                                       name="fine_number" type="text" class="text-uppercase" placeholder="{{ $documentObject['field_fine_number_placeholder'] }}" value="">
                            </div>
                        </div>

                        <div id="fines-search-errors" style="display: none"></div>
                        <div class="form-footer">

                            <input type="submit" class="btn" value="Пошук">
                        </div>


                    </form>

                </div>
            </div>
            <div class="col payment-services-right">
                <div class="text-wrap">{!! $documentObject['content'] !!}</div>
            </div>
        </div>

    </div>

    <div id="service-steps" {{ $renderMode !=='showFine'?'style="display:none;"':'' }}>
        @if(isset($showFineError))
            <div class="alert alert-danger">
                <p>{{ $showFineError }} <a class="pointer" id="fines-use-search-form">Скористатись формою пошуку</a> </p>
            </div>
        @endif
        {!! $fines !!}
    </div>
    <div id="service-preview" {!! $renderMode !=='payFine'?'style="display: none"':'' !!}>
        @if($previewFineError)
            <div class="alert alert-danger">
                <p>{{ $previewFineError }} <a class="pointer" id="fines-use-search-form">Скористатись формою пошуку</a> </p>
            </div>
        @endif
        {!! $finePreview !!}
    </div>


    <form class="js-service-form" id="service-form" style="display: none">
        <input type="hidden" name="service_id" id="service_id" value="47">
        <input type="hidden" name="fine_id" id="fine_id" value="{{ $fineId??'' }}">
    </form>


@endsection

@push('js')
    {!! $modx->runSnippet('js',[
    'minify'=>0,
    'folder'=>'theme/js/',
    'files'=>implode(',',[
       'theme/js/services/fines.js'
    ])]) !!}

@endpush