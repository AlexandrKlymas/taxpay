<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <title>{{ !empty($documentObject['titl'])?$documentObject['titl']:$documentObject['pagetitle'] }}</title>
    <meta name="description" content="{{ !empty($documentObject['desc'])?$documentObject['desc']:$documentObject['introtext'] }}">
    <meta name="keywords" content="{{ !empty($documentObject['keyw'])?$documentObject['keyw']:$documentObject['pagetitle'] }}">

    @if($documentObject['noIndex'])
        <meta name="robots" content="noindex, nofollow" />
    @endif

    <base href="{{ $config['site_url'] }}">

    <link rel="apple-touch-icon" sizes="180x180" href="/theme/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/theme/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/theme/favicon/favicon-16x16.png">
    <link rel="manifest" href="/theme/favicon/site.webmanifest">
    <link rel="mask-icon" href="/theme/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link rel="manifest" href="/evo-manifest.json">
    <script type="text/javascript">
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/evo-serviceworker.js', {
                scope: '.'
            }).then(function (registration) {
                console.log('ServiceWorker registration successful with scope: ', registration.scope)
            }, function (err) {
                console.log('ServiceWorker registration failed: ', err)
            });
        }

    </script>

    {!! $modx->runSnippet('css',[
    'minify'=>0,
    'folder'=>'theme/css/',
    'files'=>implode(',',[
        'theme/css/bootstrap-reboot.min.css',
        'theme/css/style.css',
        'theme/css/datepicker.min.css',
        'theme/css/custom.css',
        'theme/css/front-custom.css',
    ])]) !!}

    {!! evo()->getConfig('g_codes_head') !!}
</head>
<body {!! $config['site_start'] == $documentObject['id'] ?'class="main-page"':'' !!}>

    @yield('base_content')

    <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-lg modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <div class="modal-header">
                    <p class="modal-title">?????????????????? ???????????????</p>
{{--                    <p class="desc">?????????????? ???????? ?????????? ?? ???? ?????? ????????????????????????. <br>--}}
{{--                        ???????????? ????????????: ????-????: 8.00-18.30</p>--}}
                </div>
                <div class="modal-body">
                    @include('partials.form.callback.form',['thanks'=>null,'errors'=>null])
                </div>
            </div>
        </div>
    </div>

    @stack('modals')



    <script>
        var commissionConfig = {!! json_encode(isset($commission)?$commission:[]) !!};
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
    <script>
           class ApiService  {
               BASE_API_PATH = window.location.origin;

               async getRequest(route, query = ''){
                   try {
                       const res = await fetch(`${this.BASE_API_PATH}/${route}${query}`);
                       return await res.json();
                   }catch (e) {
                       console.error(e);
                   }
               }
           }

           const _Service = new ApiService();

            function debounce (fn, delay) {
                let timeoutID = null;
                return function () {
                    clearTimeout(timeoutID);
                    let args = arguments;
                    let that = this;
                    timeoutID = setTimeout(function () {
                        fn.apply(that, args)
                    }, delay)
                }
            }


           const Search = new Vue({
             el: '#searchBlock',
             data() {
                 return {
                     inputValue: '',
                     dropdownState: false,
                     searchArray: []
                 }
             },
             methods: {
                 async search(inputValue){
                    this.searchArray = await _Service.getRequest('search', `?query=${inputValue}`)
                    },
                },
             watch: {
                inputValue: debounce(function (newVal) {
                    if(newVal.trim()){
                        this.search(newVal.trim().toLowerCase());
                        this.dropdownState = true
                    }else{
                         this.dropdownState = false
                    }
                }, 500)
             },
             created() {
                 var vm = this;
                 document.addEventListener('click', function (e) {
                    if(!e.target.closest('.search')){
                       vm.dropdownState = false;
                    }
                 });
             },
           })
    </script>


    {!! $modx->runSnippet('js',[
    'minify'=>0,
    'folder'=>'theme/js/',
    'files'=>implode(',',[
        'theme/js/jquery-3.4.1.min.js',
        'theme/js/bootstrap.bundle.min.js',
        'theme/js/jquery.inputmask.bundle.min.js',
        'theme/js/script.js',

        'theme/js/form-validator/jquery.form-validator.min.js',
        'theme/js/services/jquery.maskedinput.min.js',

        'theme/js/services/online-request.js',
        'theme/js/services/amount.js',
        'theme/js/services/validation.js',
        'theme/js/services/form.js',
        'theme/js/services/fields.js',
        'theme/js/datepicker.min.js',
        'theme/js/custom.js',
    ])]) !!}

    @stack('js')
    {!! evo()->getConfig('g_codes_body') !!}
</body>
</html>
