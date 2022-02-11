
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
            <span class="row-name">Сервісний збір складає <strong>{{round($commissions['total']['percent'])}}%</strong>, мінімум <strong>{{$commissions['total']['min_summ']}} грн</strong>.</span>
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
                        @foreach($requestData as $name=>$value)
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
        @include('partials.payment_methods')
    </div>
</div>

<div class="form-footer">

    <div class="back-wrap">
        {{--        <a href="{{ redirect()->back()->getTargetUrl() }}" class="back">Назад</a>--}}
    </div>
    <input type="button" class="btn" value="Сплатити" id="temp-button" onclick="validSum()">
</div>

<script>
    let commissionsList = {!! json_encode($commissions) !!};
    let feeInfo = {
        min:Number(commissionsList.total.min_summ),
        percent: commissionsList.total.percent / 100
    };

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