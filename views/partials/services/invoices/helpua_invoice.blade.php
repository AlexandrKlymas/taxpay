
<div class="payment_table">
    <div style="padding-bottom:5px;text-align:center;"><img src="@include('partials.services.logo')" alt=""></div>
    <div class="table">
        <table align="center" width="750px" cellpadding="5" cellspacing="7">
            <tbody>
            <tr>
                <td>Receipt ID</td>
                <td class="data"><b>{{ \EvolutionCMS\Main\Support\Helpers::formattedCheckId($recipient->check_id) }}</b></td>
                <td>Date of payment</td>
                <td class="data"><b>{{ $order->liqpay_payment_date }}</b></td>
            </tr>
            <tr>
                <td colspan="2">Payer</td>
                <td colspan="2">Address</td>
            </tr>
            <tr>
                <td colspan="2" class="data"><b>{{ $order->form_data['full_name'] }}</b></td>
                <td colspan="2" class="data"><b>{{ $order->form_data['address'] }}</b></td>
            </tr>
            <tr>
                <td colspan="2">Paid</td>
                <td>Bank code</td>
                <td>USREOU</td>
            </tr>
            <tr>
                <td colspan="2" class="data"><b>{{$recipient->recipient_bank_name}}</b></td>
                <td class="data"><b>{{$recipient->mfo}}</b></td>
                <td class="data"><b>{{$recipient->edrpou}}</b></td>
            </tr>
            <tr>
                <td colspan="2">Recipient of payment</td>
                <td colspan="2">Beneficiary's bank</td>
            </tr>
            <tr>
                <td colspan="2" class="data"><b>{{ $recipient->recipient_name }}</b></td>
                <td colspan="2" class="data"><b>{{ $recipient->recipient_bank_name }}</b></td>
            </tr>
            <tr>
                <td colspan="3">Account  IBAN</td>
                <!-- <td>Код банку</td> -->
                <td>USREOU</td>
            </tr>
            <tr>
                <td colspan="3" class="data"><b>{{ $recipient->account }}</b></td>
                <!-- <td class="data"><b>[+poluch_mfo+]</b></td> -->
                <td class="data"><b>{{ $recipient->edrpou }}</b></td>
            </tr>
            <tr>
                <td><i>Purpose of payment</i></td>
                <td colspan="3" class="data"><b>{{ $recipient->purpose }}</b></td>
            </tr>
            <tr>
                <td><i>Total paid</i></td>
                <td colspan="3" class="data"><b>{{ $order->total }} USD</b></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="footer">
        <p><i><strong>We appreciate your time and trust in our service, thank you for being with us.</strong></i></p>
    </div>
    <br>
</div>