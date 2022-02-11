@extends('layout.default')

@section('content')


    <h1>{{ $documentObject['pagetitle'] }}</h1>

    <div id="service-preview" >
        @if(!empty($error))
            <div class="service-error">
                {{ $error }}
            </div>
        @endif
        {!! $preview !!}
    </div>


@endsection

