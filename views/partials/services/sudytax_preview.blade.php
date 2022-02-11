
@php
    /** @var $recipient \EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto **/
    /** @var $amount \EvolutionCMS\Main\Services\GovPay\Dto\PaymentAmountDto **/
@endphp

<div class="row">
    <div class="col-12 box-wrap">
        <div class="box">
            <h4 class="box-title">Платник</h4>

            <div class="data-row">
                <div class="data">
                    <span class="row-name">ПІБ:</span>
                    <span class="val">{{ $formData['full_name'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 box-wrap">
        <div class="box">
            <h4 class="box-title">Отримувач</h4>

            <div class="data-row">
                <div class="data">
                    <span class="row-name">ЄДРПОУ:</span>
                    <span class="val">{{ $recipient->getEdrpou() }}</span>
                </div>
            </div>
            <div class="data-row">
                <div class="data">
                    <span class="row-name">МФО:</span>
                    <span class="val">{{ $recipient->getMfo() }}</span>
                </div>
            </div>
            <div class="data-row">
                <div class="data">
                    <span class="row-name">Розрахунковий рахунок:</span>
                    <span class="val">{{ $recipient->getAccount() }}</span>
                </div>
            </div>
            <div class="data-row">
                <div class="data">
                    <span class="row-name">Назва отримувача:</span>
                    <span class="val">{{ $recipient->getRecipientName() }}</span>
                </div>
            </div>
            @if($recipient->getRecipientBankName())
                <div class="data-row">
                    <div class="data">
                        <span class="row-name">Банк отримувача:</span>
                        <span class="val">{{ $recipient->getRecipientBankName() }}</span>
                    </div>
                </div>
            @endif
            <div class="data-row">
                <div class="data">
                    <span class="row-name">Призначення платежу:</span>
                    <span class="val">{{ $recipient->getPurpose() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6 box-wrap">
        <div class="box">
            <h4 class="box-title">Оплата</h4>
            <div class="data-row">
                <div class="data data--form">
                    <span class="row-name">Сумма оплати:</span>
                    <span class="val"><span id="fee">{{ $amount->getSum() }}</span> грн</span>
                    <form class="form-payment js-service-form has-success" id="service-form">
                        <input type="hidden" name="service_id" value="{{$service_id}}">
                        <input type="hidden" name="nocache" value="{{time()}}">
                        @foreach($formData as $name=>$value)
                            <input type="hidden" name="{{$name}}" value="{{$value}}">
                        @endforeach
                    </form>
                </div>
            </div>
            <div class="data-row">
                <div class="data">
                    <span class="row-name">Сумма сервісного збору, грн:</span>
                    <span class="val"><span id="fee">{{ $amount->getServiceFee() }}</span> грн</span>
                </div>
            </div>
            <div class="data-row">
                <div class="data">
                    <span class="row-name">Всього до сплати: </span>
                    <span class="val"><span id="total">{{ $amount->getTotal() }}</span> грн</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 box-wrap">
        @include('partials.payment_methods')
    </div>
</div>

<div class="form-footer">

    <div class="back-wrap">
    </div>
    <input type="button" class="btn" value="Сплатити" id="temp-button" onclick="validSum()">
</div>

<script>
    function validSum(){
        let button = document.querySelector('#temp-button');
        if(document.querySelector('#service-form').classList.contains('has-success')){
            let i = 0;
            document.querySelectorAll('[name="payment_method"]').forEach(function (el){
                if(el.checked){
                    i++;
                }
            });
            console.log(i);
            if(i>0){
                button.setAttribute('type','submit');
                button.setAttribute('id','create-order-and-pay');
                setTimeout(function (){
                    $('#create-order-and-pay').click();
                },100);
            }else{
                alert('Оберіть спосіб оплати');
            }
        }else{
            alert('Заповніть сумму оплати');
        }
    }
</script>