@extends('layout.default')

@php
    /** @var $units \EvolutionCMS\Models\SiteContent[] */
    /** @var $service \EvolutionCMS\Models\SiteContent */
@endphp
@section('content')

    <div class="row">

        @include('partials.sidebar')
        <div class="main-content">


            @foreach($units as $unit)
            <h4>{{ $unit->pagetitle }}</h4>
            <div class="row row-services">
                @foreach($unit->services as $service)
                <div class="col item-wrap">
                    <div class="item">
                        <p class="title">{{ $service->pagetitle }}</p>
                        <a href="{{ UrlProcessor::makeUrl($service->id) }}" class="readmore"></a>
                    </div>
                </div>
                @endforeach


            </div>
            @endforeach

        </div>
    </div>


@endsection