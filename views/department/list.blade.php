
@extends('layout.default')

@php
    /** @var $departments \EvolutionCMS\Models\SiteContent[] */
@endphp
@section('content')

    <h1>Виберіть міністерство</h1>

    <div class="row row-departmets">

        @foreach($departments as $department)
        <div class="col item-wrap">
            <div class="item">
                <div class="image">
                    <a href="{{ UrlProcessor::makeUrl($department->id) }}"><img src="{{ $department->image }}" alt="{{ $department->pagetitle }}"></a>
                </div>
                <p class="title">
                    {{ $department->titleFirstWord }} <strong>{{ $department->anotherFirstWord }}</strong>
                </p>
                <a href="{{ UrlProcessor::makeUrl($department->id) }}" class="readmore"></a>
            </div>
        </div>
        @endforeach


    </div>


@endsection