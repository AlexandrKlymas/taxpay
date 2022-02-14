@extends('layout.default')

@php
    /** @var $units \EvolutionCMS\Models\SiteContent[] */
    /** @var $service \EvolutionCMS\Models\SiteContent */
@endphp
@section('content')


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
                        <p class="title">ЗАПОВНИТИ ФОРМУ:</p>
                        <form class="form-payment js-service-form service-{{ $serviceId }}" id="service-form" data-validation-form>
                            <input type="hidden" name="service_id" value="{{ $serviceId }}">

                            <div id="service-form-errors"></div>

                            {!! $form !!}


                            <p>Натискаючи кнопку "Продовжити" Ви погоджуєтеся з умовами <a href="{{ UrlProcessor::makeUrl(7) }}" target="_blank" >публічного договору</a>.</p>

                            <div class="form-footer">
                                <div class="back-wrap">
                                    <a href="{{ \EvolutionCMS\Facades\UrlProcessor::makeUrl(3) }}" class="back">Назад</a>
                                </div>
                                <input type="submit" class="btn" value="Продовжити">
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            <div class="col payment-services-right">
                <div class="text-wrap">{!! $documentObject['content'] !!}</div>
            </div>
        </div>
    </div>
    <div id="service-preview" style="display: none">

    </div>


@endsection

