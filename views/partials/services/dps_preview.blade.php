
@php
    /** @var $recipient \EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto **/
    /** @var $amount \EvolutionCMS\Main\Services\GovPay\Dto\PaymentAmountDto **/
@endphp

<div class="box verification-box">
    <div class="row">
        <div class="col-6">
            <h4 class="box-title">Платник</h4>

            @foreach($formData as $caption => $value)
                <div class="data-row">
                    <div class="data">
                        <span class="row-name">{{ $caption }}:</span>
                        <span class="val">{{ $value }}</span>
                    </div>
                </div>
            @endforeach
{{--            //TODO: make dinamic fee--}}
            <span class="row-name">Сервісний збір складає <strong>2%</strong>, мінімум <strong>9 грн</strong>.</span>
        </div>
        <div class="col-6">
            <h4 class="box-title">Отримувач</h4>

            <div class="data-row">
                <div class="data">
                    <span class="row-name">ЄДРПОУ:</span>
                    <span class="val">{{ $recipient->getEdrpou() }}</span>
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
                    <form class="form-payment js-service-form" id="service-form" data-validation-form>
                        @foreach($request as $name=>$value)
                            @if($name=='sum')
                                <input id="sum" name="sum" type="text"
                                       @if(!empty($value)) value="{{$value}}" @endif
                                       oninput="calc()"
                                       style="width: 100px;"
                                       class="js-sum-field"
                                       placeholder="0.00"
                                       data-validation="required minsumm"
                                       data-validation-minsumm="1"
                                       data-validation-errormsgminsumm="Сума менше мінімальної"
                                       data-validation-pattern="^(\d{1,}\.?\d?\d?)$"
                                       data-validation-errormsg="Невірний формат"
                                       data-validation-has-keyup-event="true"
                                ><span class="val"> грн</span>
                            @else
                            <input type="hidden" name="{{$name}}" value="{{$value}}">
                            @endif
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
        <div class="box box-payment-metods">
            <h4 class="box-title">Оберіть спосіб оплати:</h4>
            <div class="radio-group payment-metods ">
                <div class="metod">
                    <input id="method1" name="payment_method" type="radio" value="gpay">
                    <label for="method1"><img src="/theme/images/g-pay-md.png" alt=""></label>
                </div>
                <div class="metod flex-50">
                    <input id="method5" name="payment_method" type="radio" value="card">
                    <label for="method5"><img src="/theme/images/card.png" alt=""><span>Сплатити карткою</span></label>
                </div>
                <div class="metod">
                    <input id="method2" name="payment_method" type="radio" value="apay">
                    <label for="method2"><img src="/theme/images/apple-pay-md.png" alt=""></label>
                </div>
                <div class="metod">
                    <input id="method6" name="payment_method" type="radio" value="ppay">
                    <label for="method6"><img src="/theme/images/privat-24.png" alt=""></label>
                </div>
                @if(!empty($error))
                    <div class="service-error">
                        {{ $error }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<div class="form-footer">

    <div class="back-wrap">
{{--        <a href="{{ redirect()->back()->getTargetUrl() }}" class="back">Назад</a>--}}
    </div>
    <input type="button" class="btn" value="Сплатити" id="temp-button" onclick="validSum()">
</div>

<script>
    let feeInfo = {!! $commissions !!};
    function calc() {
        let sum = document.getElementById('sum').value;
        let sumdot = sum.replace(',', '.');
        let fee = (sumdot * feeInfo.percent).toFixed(2);
        if(fee<feeInfo.min){
            fee = feeInfo.min;
        }
        document.getElementById('fee').textContent = fee;
        document.getElementById('total').textContent = Number(sumdot) + Number(fee);
        document.getElementById('sum').value = sumdot;
    }

    document.addEventListener("DOMContentLoaded", function(){
        $('[name="sum"]').validate();
    });
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