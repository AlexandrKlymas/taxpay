@extends('layout')

@section('content')

    <h2>{{$service['pagetitle']}} <button type="submit" class="btn btn-success" onclick="add_sub_service()">
            <i class="fa fa-plus"></i> Додати дочірній сервіс</button></h2>

    @if(!empty($sub_services))
        <div class="list-group">
            @foreach($sub_services as $sub_service)
                <div class="list-group-item list-group-item-action">
                    <a href="{{$module_url}}&action=sub_service&service_id={{$service['id']}}&sub_service_id={{$sub_service['id']}}"
                       class="badge-light p-1"
                       data-sub_service_id="{{$sub_service['id']}}">{{$sub_service['name']}}</a>
                    <button class="btn btn-warning" type="button"
                            onclick="edit_sub_service({{$sub_service['id']}})">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-danger" type="button"
                            onclick="delete_sub_service({{$sub_service['id']}})">
                        <i class="fa fa-remove"></i>
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-5">
        @include('partials.service_recipients_list',['_service_recipients'=>$service_recipients,'_module_url'=>$module_url])
    </div>

    <div class="mt-5">
        @include('partials.new_recipient_form',['_service'=>$service])
    </div>


@endsection
