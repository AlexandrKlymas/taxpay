@extends('layout')

@section('content')

    <div class="mt-5">
        <h1>Сервіси</h1>

        <div class="list-group">
            @foreach($services as $service)
                <a href="{{$module_url}}&action=service&service_id={{$service['id']}}"
                   class="list-group-item list-group-item-action">
                    {{$service['pagetitle']}}
                </a>
            @endforeach
        </div>
    </div>


    <div class="mt-5">
        <h2>Отримувачі комісій <button type="submit" class="btn btn-warning"
                                      onclick="window.location='{{$module_url}}action=commissions_recipients'">
                <i class="fa fa-edit"></i> Редагувати</button></h2>

        <div class="list-group">
            @foreach($commissions_recipients as $commissions_recipient)
                <span>{{$commissions_recipient['recipient_name']}}</span>
            @endforeach
        </div>
    </div>


@endsection