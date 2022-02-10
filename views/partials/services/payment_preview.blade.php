@extends('layout.default')

@php
    /** @var $recipient \EvolutionCMS\Services\Contracts\IPaymentRecipient **/
    /** @var $amount \EvolutionCMS\Services\Contracts\IPaymentAmount **/
@endphp

@section('content')

    <h1>Оплата послуг Поліції охорони</h1>

    <h3>Перевірте правильність введених даних та оберiть спосiб оплати</h3>
    <div class="box verification-box">
        <div class="row">
            <div class="col-6">
                <h4 class="box-title">До сплати</h4>

                @foreach($formData as $field)

                <div class="data-row">
                    <div class="data">
                        <span class="row-name">{{ $field['caption'] }}:</span>
                        <span class="val">{{ $field['value'] }}</span>
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
                        <span class="val">{{ $recipient->getName() }}</span>
                    </div>
                </div>
                <div class="data-row">
                    <div class="data">
                        <span class="row-name">Банк отримувача:</span>
                        <span class="val">{{ $recipient->getBank() }}</span>
                    </div>
                </div>
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
                <h4 class="box-title">Оплати</h4>
                <div class="data-row">
                    <div class="data">
                        <span class="row-name">Сумма оплати:</span>
                        <span class="val">{{ $amount->getSum() }} грн</span>

                    </div>

                </div>
                <div class="data-row">
                    <div class="data">
                        <span class="row-name">Сумма сервісного збору, грн:</span>
                        <span class="val">{{ $amount->getServiceTax() }} грн</span>
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
                <div class="radio-group payment-metods">
                    <div class="metod">
                        <input id="method1" name="payment" type="radio">
                        <label for="method1"><img src="/theme/images/g-pay-md.png" alt=""></label>
                    </div>
                    <div class="metod">
                        <input id="method2" name="payment" type="radio">
                        <label for="method2"><img src="/theme/images/apple-pay-md.png" alt=""></label>
                    </div>
                    <div class="metod">
                        <input id="method3" name="payment" type="radio">
                        <label for="method3"><img src="/theme/images/masterpass-md.png" alt=""></label>
                    </div>
                    <div class="metod">
                        <input id="method4" name="payment" type="radio">
                        <label for="method4"><img src="/theme/images/visa-checkout-md.png" alt=""></label>
                    </div>
                    <div class="metod full">
                        <input id="method5" name="payment" type="radio">
                        <label for="method5"><img src="/theme/images/card.png" alt=""><span>Сплатити картою</span></label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-footer">
        <div class="back-wrap">
            <a href="/service-preview-back?paymentId={{ $paymentId }}" class="back">Назад</a>
        </div>
        <input type="submit" class="btn" value="Продовжити">
    </div>



@endsection