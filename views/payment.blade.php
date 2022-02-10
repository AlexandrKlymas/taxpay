@extends('layout.default')

@section('content')

    <div class="wrap-spin">
        <div class="loader-spin">Loading...</div>
    </div>

    <div id="liqpay_checkout"></div>

    <script>
        let qStatusCounter = 0;
        window.LiqPayCheckoutCallback = function () {
            LiqPayCheckout.init({
                data: "{!! $paymentParams['data'] !!}",
                signature: "{!! $paymentParams['signature'] !!}",
                embedTo: "#liqpay_checkout",
                language: "uk",
                mode: "embed" // embed || popup
            }).on("liqpay.callback", function (data) {

                data.controllerResponseType = 'json';

                $('#liqpay_checkout').hide();

                setTimeout(function(){
                    $('.wrap-spin').toggleClass('active');
                },300);

                $.post('/liqpay-result', data, function (response) {
                    if(response.redirect){
                        window.location = response.redirect;
                    }
                });

            });
        };

    </script>
    <script src="//static.liqpay.ua/libjs/checkout.js" async></script>

@endsection
@push('addClass'){{'wrap-iframe'}}@endpush