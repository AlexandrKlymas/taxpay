@extends('layout.default')

@section('content')
    <div class="row">
        @include('partials.sidebar')
        <div class="main-content">
            <h1>Оплата державних послуг</h1>

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