@php
    /** @var $order \EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder */
    /** @var $recipients array */
    /** @var $recipient \EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient */
@endphp

<div class="payment_table">
    <div style="padding-bottom:5px;text-align:center;"><img src="@include('partials.services.logo')" alt=""></div>
    @foreach($recipients as $recipient)
        <div class="table">
            <div style="padding-bottom:15px;text-align:center;">
            <table align="center" width="750px" cellpadding="5" cellspacing="7">
                <tbody>
                <tr>
                    <td>Квитанція ID</td>
                    <td class="data"><b>{{ \EvolutionCMS\Main\Support\Helpers::formattedCheckId($recipient->check_id) }}</b></td>
                    <td>Дата оплати</td>
                    <td class="data"><b>{{ $order->liqpay_payment_date }}</b></td>
                </tr>
                <tr>
                    <td colspan="2">Платник</td>
                    <td colspan="2">Адреса</td>
                </tr>
                <tr>
                    <td colspan="2" class="data"><b>
                            {{ $order->form_data['full_name'] }}
                        </b></td>
                    <td colspan="2" class="data"><b>{{ $order->form_data['address'] }}</b></td>
                </tr>
                <tr>
                    <td colspan="2">Сплачено</td>
                    <td>Код банку</td>
                    <td>ЄДРПОУ</td>
                </tr>
                <tr>
                    <td colspan="2" class="data"><b>АТ "БАНК ТРАСТ-КАПІТАЛ"</b></td>
                    <td class="data"><b>380106</b></td>
                    <td class="data"><b>39048249</b></td>
                </tr>
                <tr>
                    <td colspan="2">Одержувач платежу</td>
                    <td colspan="2">Банк одержувача</td>
                </tr>
                <tr>
                    <td colspan="2" class="data"><b>{{ $recipient->recipient_name }}</b></td>
                    <td colspan="2" class="data"><b>{{ $recipient->recipient_bank_name }}</b></td>
                </tr>
                <tr>
                    <td colspan="3">Рахунок  IBAN</td>
                    <td>ЄДРПОУ</td>
                </tr>
                <tr>
                    <td colspan="3" class="data"><b>{{ $recipient->account }}</b></td>
                    <td class="data"><b>{{ $recipient->edrpou }}</b></td>
                </tr>
                <tr>
                    <td><i>Призначення платежу</i></td>
                    <td colspan="3" class="data"><b>{{ $recipient->purpose }}</b></td>
                </tr>
                <tr>
                    <td><i>Сума</i></td>
                    <td colspan="3" class="data"><b>{{ \EvolutionCMS\Main\Services\GovPay\Support\AmountHelpers::amountInWord($order->sum) }}</b></td>
                </tr>
                <tr>
                    <td><i>Сервісний збір</i></td>
                    <td colspan="3" class="data"><b>{{ \EvolutionCMS\Main\Services\GovPay\Support\AmountHelpers::amountInWord($order->service_fee) }}</b></td>
                </tr>
                <tr>
                    <td><i>Всього сплачено</i></td>
                    <td colspan="3" class="data"><b>{{ \EvolutionCMS\Main\Services\GovPay\Support\AmountHelpers::amountInWord($order->total) }}</b></td>
                </tr>
                </tbody>
            </table>
        </div>
    @endforeach
    <div class="footer">
        <p><i><strong>Ми цінуємо Ваш час та довіру до нашого сервісу, дякуємо що Ви з нами.</strong></i></p>
    </div>
    <br>
</div>
