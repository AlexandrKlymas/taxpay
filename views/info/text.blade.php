@extends('layout.default')

@section('content')


    @foreach($content as $block)
        @include('partials.multifields.text.'.$block['row_name'],['block'=>$block])
    @endforeach



@endsection