
@php
    /** @var $recipient \EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto **/
    /** @var $amount \EvolutionCMS\Main\Services\GovPay\Dto\PaymentAmountDto **/
@endphp


<h3>Перевірте правильність введених даних</h3>
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
                <div class="data">
                    <span class="row-name">Сумма оплати:</span>
                    <span class="val">{{ $amount->getSum() }} грн</span>

                </div>

            </div>
            <div class="data-row">
                <div class="data">
                    <span class="row-name">Сумма сервісного збору, грн:</span>
                    <span class="val">{{ $amount->getServiceFee() }} грн</span>
                </div>
            </div>
            <div class="data-row">
                <div class="data">
                    <span class="row-name">Всього до сплати: </span>
                    <span class="val">{{ $amount->getTotal() }} грн</span>
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

            </div>
        </div>
    </div>
</div>

<div class="form-footer">

    <div class="back-wrap">
        @if(!isset($hideBackLink))
        <a id="service-back" class="back">Назад</a>
        @endif
    </div>
    <input type="submit" class="btn" value="Сплатити" id="create-order-and-pay">
</div>



