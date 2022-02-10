@extends('layout.default')


@section('content')

    <h1>{!! $documentObject['longtitle'] !!}</h1>
    <div class="row successful-row">
        <div class="col image">
            <img src="/theme/images/successful.svg" alt="">
        </div>
        <div class="col text-wrap text">
            {!! $documentObject['content'] !!}


            <div class="successful-footer">
{{--                <button type="button" class="successful-btn to-email" data-toggle="modal" data-target="#sendToEmail">--}}
{{--                    Вiдправити договір на Email--}}
{{--                </button>--}}
{{--                <span>Або</span>--}}
                <a href="{{ $invoice_pdf }}" target="_blank" class="successful-btn to-print">
                    Роздрукувати договір
                </a>
            </div>
{{--            <div class="successful-footer">--}}
{{--                <button type="button" class="successful-btn to-email" data-toggle="modal" data-target="#sendToEmail">--}}
{{--                    Вiдправити квитанцію на Email--}}
{{--                </button>--}}
{{--                <span>Або</span>--}}
{{--                <a href="{{ $invoice_pdf }}" target="_blank" class="successful-btn to-print">--}}
{{--                    Роздрукувати квитанцію--}}
{{--                </a>--}}
{{--            </div>--}}


            @if(evo()->getConfig('prod') === true)
                <script> gtag('event', 'conversion', { 'send_to': 'AW-398163744/LxVCCJGujP0BEKD-7b0B', 'transaction_id': '{{ $liqpay_transaction_id }}' }); </script>
            @endif




        </div>
    </div>

@endsection

@push('modals')

    <div class="modal fade" id="sendToEmail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-sm modal-dialog modal-dialog-centered send-to-email" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <p class="modal-title">Надіслати квитанцію на Email</p>
                </div>
                <div class="modal-body">
                    @include('partials.form.invoiceToEmail.form',[
                        'invoice' =>$invoice_pdf
                    ])
                </div>
            </div>
        </div>
    </div>

@endpush