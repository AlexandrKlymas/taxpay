@extends('layout.default')

@php
    /** @var $units \EvolutionCMS\Models\SiteContent[] */
    /** @var $service \EvolutionCMS\Models\SiteContent */
@endphp
@section('content')


    <h1>{{ $documentObject['pagetitle'] }}</h1>

    <div id="service-preview" >
        {!! $preview !!}
    </div>


@endsection

