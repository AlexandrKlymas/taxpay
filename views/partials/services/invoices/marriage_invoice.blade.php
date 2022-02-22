<div class="payment_table">
    <div style="padding-bottom:5px;text-align:center;"><img src="@include('partials.services.logo')" alt=""></div>
    @foreach($recipients->getRecipientList() as $recipient)
        <div class="table">
            <div style="padding-bottom:15px;text-align:center;"></div>
            <table align="center" width="750px" cellpadding="5" cellspacing="7">
                <tbody>
                <tr>
                    <td>Квитанція ID</td>
                    <td class="data">
                        <b>{{ \EvolutionCMS\Main\Support\Helpers::formattedCheckId($recipient->getCheckId()) }}</b></td>
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
                    <td colspan="2" class="data"><b>{{ $recipient->getRecipient()->recipient_name }}</b></td>
                    <td colspan="2" class="data"><b>{{ $recipient->getRecipientBankName() }}</b></td>
                </tr>
                <tr>
                    <td colspan="3">Рахунок IBAN</td>
                    <td>ЄДРПОУ</td>
                </tr>
                <tr>
                    <td colspan="3" class="data"><b>{{ $recipient->getRecipient()->iban }}</b></td>
                    <td class="data"><b>{{ $recipient->getRecipient()->edrpou }}</b></td>
                </tr>
                <tr>
                    <td><i>Призначення платежу</i></td>
                    <td colspan="3" class="data">
                        <b>{{\EvolutionCMS\Main\Services\GovPay\Support\PurposeHelpers::parse($recipient->getRecipient()->purpose_template,$order->form_data) }}</b>
                    </td>
                </tr>
                <tr>
                    <td><i>Сума</i></td>
                    <td colspan="3" class="data">
                        <b>{{ \EvolutionCMS\Main\Services\GovPay\Support\AmountHelpers::amountInWord($recipient->getRecipient()->sum) }}</b>
                    </td>
                </tr>
                <tr>
                    <td><i>Сервісний збір</i></td>
                    <td colspan="3" class="data">
                        <b>{{ \EvolutionCMS\Main\Services\GovPay\Support\AmountHelpers::amountInWord($recipient->getGPCommission()) }}</b>
                    </td>
                </tr>
                <tr>
                    <td><i>Всього сплачено</i></td>
                    <td colspan="3" class="data">
                        <b>{{ \EvolutionCMS\Main\Services\GovPay\Support\AmountHelpers::amountInWord($recipient->getRecipient()->sum + $recipient->getGPCommission()) }}</b>
                    </td>
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
