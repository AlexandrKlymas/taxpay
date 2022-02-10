@extends('layout.default')

@section('content')


    <h1>{{ $documentObject['pagetitle'] }}</h1>
    <div class="row contacts-row">
        <div class="col-6">
            <div class="box contacts-box">{!! $documentObject['content'] !!}</div>
        </div>
        <div class="col-6">
            <div class="box map-box">
                <div class="map">
                    <div class="marker" data-lat="50.4158723" data-lng="30.5478333"></div>
                </div>
            </div>
        </div>

        <div class="col-12" >
            <div class="box contacts-box">
            {!! $documentObject['additional_content'] !!}
            </div>
        </div>
        <div class="col-12" style="display: none">
            <div class="box contacts-form">
                <h3>Зворотній зв’язок</h3>
{{--                <p class="desc">Залиште свій номер і ми вам передзвонимо. <br> Графік роботи: Пн-Пт: 8.00-18.30</p>--}}

                @if(isset($errors))
                    <div class="alert alert-danger">
                    @foreach($errors->all() as $message)
                        {{ $message }} <br>
                    @endforeach
                    </div>
                @endif

                @if($thanks)
                    <div class="alert alert-success">
                        {{ $thanks }}
                    </div>
                @endif

                <form action="{{ UrlProcessor::makeUrl($documentObject['id']) }}" method="post">
                    <input type="hidden" name="formid" value="contacts" />

                    <div class="row form-row">
                        <div class="col-4 form-group">
                            <label for="yourName">ім`я</label>
                            <input id="yourName" name="name" type="text" placeholder="Введите имя" value="{{ $form['name'] }}">
                        </div>
                        <div class="col-4 form-group">
                            <label for="yourTel">Телефон</label>
                            <input id="yourTel" class="input-phone" name="phone" type="tel" placeholder="+38 (___) ___-__-__" value="{{ $form['phone'] }}">
                        </div>
                        <div class="col-4 form-group">
                            <label for="yourEmail">Email</label>
                            <input id="yourEmail" name="email" type="email" placeholder="Name@gmail.com" value="{{ $form['email'] }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message">Повідомлення</label>
                        <textarea id="message" name="message" placeholder="Введіть повідомлення" required>{{ $form['message'] }}</textarea>
                    </div>

                    <input type="submit" class="btn" value="Надiслати">
                </form>
            </div>
        </div>
    </div>

@push('js')
    <script src="/theme/js/map.js?v=2.1"></script>
    <script src="//maps.google.com/maps/api/js?key=AIzaSyBTJQH5lyseCKQlITM5fkmkFCgIob4LdRA&ver=5.0.2&callback=initMap"></script>

@endpush
@endsection
