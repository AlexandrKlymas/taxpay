@extends('layout.base')

@section('base_content')

    <body class="main-page">
    <div id="wrapper">
        <div class="main-block">
            <p class="title">Портал оплати</p>
            <p class="subtitle"><span>державних та муніципальних послуг</span></p>
            <p>Автоматизована<br> інформаційно-сервісна система</p>
            <a href="{{ UrlProcessor::makeUrl(3) }}" class="btn">Сплатити</a>
        </div>
    </div>
    </body>

@endsection