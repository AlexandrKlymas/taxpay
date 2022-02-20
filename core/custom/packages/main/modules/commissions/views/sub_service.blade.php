@extends('layout')

@section('content')

    <h2>{{$sub_service['name']}}</h2>

    <div class="mt-5">
        @include('partials.service_recipients_list',['_service_recipients'=>$sub_service_recipients,'_module_url'=>$module_url])
    </div>

    <div class="mt-5">
        @include('partials.new_recipient_form',['_service'=>$service,'_sub_service'=>$sub_service])
    </div>

@endsection
