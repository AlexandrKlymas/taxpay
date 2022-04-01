@extends('layout.default')

@section('content')
    @push('styles')
        <style>
            /* Tooltip container */
            .tooltip {
                position: relative;
            }

            /* Tooltip text */
            .tooltip .tooltiptext {
                visibility: hidden;
                width: 280px;
                background-color: #1F4E78;
                color: #fff;
                text-align: center;
                padding: 10px;
                border-radius: 6px;

                /* Position the tooltip text - see examples below! */
                position: absolute;
                top: 0;
                z-index: 1;
            }

            /* Show the tooltip text when you mouse over the tooltip container */
            /*.tooltip:hover .tooltiptext {*/
            /*    visibility: visible;*/
            /*}*/
        </style>
    @endpush
    <h1>{!! $documentObject['longtitle'] !!}</h1>
    <div class="row successful-row">
        <div class="col image">
            <img src="/theme/images/successful.svg" alt="">
        </div>
        <div class="col text-wrap text">
            {!! $documentObject['content'] !!}


            <div class="successful-footer">
                <button type="button" class="successful-btn to-email" data-toggle="modal" data-target="#sendToEmail">
                    Вiдправити квитанцію на Email
                </button>
                <span>Або</span>
                <a href="{{ $invoice_pdf }}" target="_blank" class="successful-btn to-print">
                    Роздрукувати квитанцію
                </a>
            </div>

            <div class="successful-footer">
                <a href="https://g.page/r/CdkOit5wOm2XEAg/review" target="_blank" class="successful-btn btn-review">
                    <span class="btn-review__text btn-review__text--desktop">
                        Нам цікава Ваша думка про сервіс<br>
                        Залиште будь ласка свій відгук
                    </span>
                    <span class="btn-review__text btn-review__text--mobile">
                        Ваш відгук та думка про сервіс
                    </span>
                </a>

                <button class="successful-btn btn-pwa tooltip" data-pwa-btn style="display: none">Додати GOVPAY24
                    на головный экран
                    <div class="tooltiptext">Для того щоб встановити додаток необхідно натиснути в панелі інструментів
                        Safari іконку <img width="50" height="50" src="/theme/images/share-icon.jpg" alt=""></div>
                </button>
            </div>

{{--            @if(!empty($backref))--}}
{{--                <div class="successful-footer">--}}
{{--                    <a href="{{$backref}}" class="successful-btn btn-review-noicon">--}}
{{--                        Повернутись до {{$backref_caption}}--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            @endif--}}

            @if(evo()->getConfig('prod') === true)
                <script> gtag('event', 'conversion', {
                        'send_to': 'AW-398163744/LxVCCJGujP0BEKD-7b0B',
                        'transaction_id': '{{ $liqpay_transaction_id }}'
                    }); </script>
            @endif


        </div>
    </div>

    <script>
        function addToHomeScreenPlease() {
            deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    console.log('User accepted the A2HS prompt');
                } else {
                    console.log('User dismissed the A2HS prompt');
                }
                deferredPrompt = null;
            });
        }
    </script>

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
                    @include('partials.form.invoiceToEmail.form',['order_hash' =>$order_hash,])
                </div>
            </div>
        </div>
    </div>

@endpush

@push('js')
    <script>
        // Detects if device is on iOS
        const isIos = () => {
            const userAgent = window.navigator.userAgent.toLowerCase();
            return [
                    'iPad Simulator',
                    'iPhone Simulator',
                    'iPod Simulator',
                    'iPad',
                    'iPhone',
                    'iPod'
                ].includes(navigator.platform)
                // iPad on iOS 13 detection
                || (navigator.userAgent.includes("Mac") && "ontouchend" in document)
            // return /iphone|ipad|ipod/.test( userAgent );
        }
        // Detects if device is in standalone mode
        const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);
        let btn = document.querySelector('[data-pwa-btn]');
        // Checks if should display install popup notification:
        if (isIos() && !isInStandaloneMode()) {
            btn.style.display = 'flex';
            document.querySelector('[data-pwa-btn]').addEventListener('click', function () {
                this.querySelector('.tooltiptext').style.visibility = 'visible';
                setTimeout(() => {
                    this.querySelector('.tooltiptext').style.visibility = 'hidden';
                }, 3000)
            });
        } else if ((/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/).test(navigator.userAgent) || (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i).test(navigator.userAgent)) {
            let deferredPrompt;
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                // Stash the event so it can be triggered later.
                deferredPrompt = e;
                btn.style.display = 'flex';
                btn.addEventListener('click', async () => {
                    // Show the install prompt
                    deferredPrompt.prompt();
                    // Wait for the user to respond to the prompt
                    const {outcome} = await deferredPrompt.userChoice;
                    // Optionally, send analytics event with outcome of user choice
                    console.log(`User response to the install prompt: ${outcome}`);
                    if (outcome === 'accepted') {
                        btn.style.display = 'none';
                    }
                    // We've used the prompt, and can't use it again, throw it away
                    deferredPrompt = null;
                });
            });
        }
    </script>
@endpush
