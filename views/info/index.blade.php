@extends('layout.base')

@section('base_content')

    <body class="main-page">
    <div id="wrapper">
        <div class="main-block">
            <p class="title">Портал оплати</p>
            <p class="subtitle"><span>державних та муніципальних послуг</span></p>
            <p>Автоматизована<br> інформаційно-сервісна система</p>
            <div><a href="{{ UrlProcessor::makeUrl(3) }}" class="btn">Сплатити</a></div>
            <div  style="margin-top:30px"><a href="#zsu" class="btn">Збір коштів на потреби армії України</a></div>
        </div>
    </div>

    <div id="zsu" style="background-color: #ffff99; text-align: center">

        <h2 style="padding-top:10px">Допомога Збройним Силам України</h2>
        <h3 style="padding-bottom:10px; margin-bottom:0px">спецрахунок НБУ</h3>
    </div>
    <div id="liqpay_checkout"></div>
    <script>
        window.LiqPayCheckoutCallback = function() {
            LiqPayCheckout.init({
                data: "eyJ2ZXJzaW9uIjozLCJhY3Rpb24iOiJwYXlkb25hdGUiLCJhbW91bnQiOiIwIiwiY3VycmVuY3kiOiJVQUgiLCJkZXNjcmlwdGlvbiI6ItCX0LHRltGAINC60L7RiNGC0ZbQsiDQvdCwINC/0L7RgtGA0LXQsdC4INCw0YDQvNGW0Zcg0KPQutGA0LDRl9C90LgiLCJwdWJsaWNfa2V5IjoiaTE3NDcyMDU5OTY0IiwibGFuZ3VhZ2UiOiJlbiJ9",
                signature: "odQHbEoIeJQUk7siDvLoNro3RGY=",
                embedTo: "#liqpay_checkout",
                mode: "embed" // embed || popup,
            }).on("liqpay.callback", function(data){
                console.log(data.status);
                console.log(data);
            }).on("liqpay.ready", function(data){
                // ready
            }).on("liqpay.close", function(data){
                // close
            });
        };
    </script>
    <script src="//static.liqpay.ua/libjs/checkout.js" async></script>

    </body>

@endsection