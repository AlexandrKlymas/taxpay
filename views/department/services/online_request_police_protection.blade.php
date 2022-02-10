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
                <div class="box box-form" id="online-request">
                    <p class="title">ЗАПОВНИТИ ФОРМУ:</p>
                    <form class="form-payment" id="online-request-form" data-validation-form >

                        <div id="request-form-errors"></div>

                        {!! $form !!}


                        <p>Натискаючи кнопку "Продовжити" Ви погоджуєтеся з умовами <a href="{{ UrlProcessor::makeUrl(7) }}" target="_blank" >публічного договору</a>.</p>

                        <div class="form-footer">
                            <div class="back-wrap">

                            </div>
                            <input type="submit" class="btn" value="Продовжити">
                        </div>
                    </form>
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

