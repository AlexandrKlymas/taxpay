@extends('layout.default_en')

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

                    @if(!empty($form))
                        <p class="title">FILL IN THE FORM:</p>
                        <form class="form-payment js-service-form service-{{ $serviceId }}" id="service-form" data-validation-form>
                            <input type="hidden" name="service_id" value="{{ $serviceId }}">

                            <div id="service-form-errors"></div>

                            {!! $form !!}

                            <p>By clicking the "Continue" button, you agree to the terms of the <a href="{{ UrlProcessor::makeUrl(7) }}" target="_blank" >public contract</a>.</p>

                            <div class="form-footer">
                                <div class="back-wrap">
{{--                                    <a href="{{ UrlProcessor::makeUrl(3) }}" class="back">Back</a>--}}
                                </div>
                                <input type="submit" class="btn" value="Continue">
                            </div>
                        </form>
                    @endif
                </div>
                <div class="text-wrap" id="left-text">{!! $documentObject['additional_content'] !!}</div>
            </div>
            <div class="col payment-services-right">
                <div class="text-wrap">{!! $documentObject['content'] !!}</div>
            </div>
        </div>
    </div>
    <div id="service-preview" style="display: none">

    </div>

@endsection