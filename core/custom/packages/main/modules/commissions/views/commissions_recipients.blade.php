@extends('layout')

@section('content')
    <div class="mt-5">
        @include('partials.commissions_recipients_list',['_commissions_recipients'=>$commissions_recipients])
    </div>
    <div class="mt-5">
        <h3>Додати нового отримувача комісій</h3>
        @include('partials.new_commission_recipient_form')
    </div>

@endsection