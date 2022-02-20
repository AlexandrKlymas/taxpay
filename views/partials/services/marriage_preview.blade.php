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
                    <span class="row-name">Всього до сплати: </span>
                    <span class="val">{{ $amount->getTotal() }} грн</span>
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
        @if(!isset($hideBackLink))
            <a id="service-back" class="back">Назад</a>
        @endif
    </div>
    <input type="submit" class="btn" value="Сплатити" id="create-order-and-pay">
</div>



