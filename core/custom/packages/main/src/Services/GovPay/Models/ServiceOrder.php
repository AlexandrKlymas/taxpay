<?php

namespace EvolutionCMS\Main\Services\GovPay\Models;


use Carbon\Carbon;
use EvolutionCMS\Main\Services\GovPay\Dto\CommissionDto;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentAmountDto;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusSubmitted;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusSuccess;
use EvolutionCMS\Main\Services\GovPay\Statuses\StatusWait;
use EvolutionCMS\Main\Support\Helpers;
use Ramsey\Uuid\Uuid;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder
 *
 * @property int $id
 * @property int $service_id
 * @property string $order_hash
 * @property array|null $form_data
 * @property array|null $service_data
 * @property string $full_name
 * @property string $phone
 * @property string $email
 * @property string $status
 * @property string|null $payment_hash
 * @property \Illuminate\Support\Carbon|null $liqpay_payment_date
 * @property array|null $liqpay_response
 * @property string|null $liqpay_real_commission
 * @property string|null $liqpay_transaction_id
 * @property string|null $liqpay_status
 * @property float $sum
 * @property string $service_fee
 * @property float $total
 * @property string $liqpay_commission_auto_calculated
 * @property string $bank_commission
 * @property string $profit
 * @property string $invoice_file_html
 * @property string $invoice_file_pdf
 * @property string|null $user_geo
 * @property string|null $history
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient[] $mainRecipients
 * @property-read int|null $main_recipients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient[] $recipients
 * @property-read int|null $recipients_count
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereBankCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereFormData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereInvoiceFileHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereInvoiceFilePdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereLiqpayCommissionAutoCalculated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereLiqpayPaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereLiqpayRealCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereLiqpayResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereLiqpayStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereOrderHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder wherePaymentHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereServiceData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereServiceFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereUserGeo($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOrder whereLiqpayTransactionId($value)
 */
class ServiceOrder extends \Eloquent
{


    protected $guarded = ['id'];

    protected $dates = [
        'liqpay_payment_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'form_data' => 'array',
        'service_data' => 'array',
        'liqpay_response' => 'array',
        'history' => 'array',

        'sum' => 'float',
        'total' => 'float'
    ];


    public static function new(int $serviceId, array $formData, PaymentAmountDto $paymentAmountDto, CommissionDto $commissions)
    {
        return self::create([
            'order_hash' => Uuid::uuid1()->toString(),
            'payment_hash' => Uuid::uuid1()->toString(),

            'service_id' => $serviceId,
            'form_data' => $formData,

            'sum' => $paymentAmountDto->getSum(),
            'service_fee' => $paymentAmountDto->getServiceFee(),
            'total' => $paymentAmountDto->getTotal(),

            'liqpay_payment_date' => Carbon::createFromTimestamp(time()),

            'liqpay_commission_auto_calculated' => $commissions->getLiqpayCommissionAutoCalculated(),
            'bank_commission' => $commissions->getBankCommission(),
            'profit' => $commissions->getProfit(),

            'status' => StatusWait::getKey(),
            'user_geo' => Helpers::getUserGeo(),
            'history'=>[
                'Запис створено '.date('d-m-Y h:i:s',
                    strtotime('now')),'статус: ['.StatusWait::getKey().'] '.(new StatusWait())->getTitle() . ', '
                .date('d-m-Y h:i:s',strtotime('now'))],

        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|self[]
     */
    public static function getPaidServiceOrders()
    {
        return ServiceOrder::where('status', StatusSuccess::getKey())->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|self[]
     */
    public static function getSubmittedServiceOrders()
    {
        return ServiceOrder::where('status', StatusSubmitted::getKey())->get();
    }

    public static function findByPaymentHash($hash)
    {
        return ServiceOrder::firstWhere('payment_hash', $hash);
    }

    public function mainRecipients()
    {
        return $this->hasMany(PaymentRecipient::class)->where('recipient_type', 'main');
    }


    public function isAllPaymentsFinished()
    {
        $allPaymentsFinished = true;
        foreach ($this->recipients as $recipient) {
            if ($recipient->status !== PaymentRecipient::STATUS_FINISHED) {
                $allPaymentsFinished = false;
            }
        }
        return $allPaymentsFinished;
    }

    public function recipients()
    {
        return $this->hasMany(PaymentRecipient::class);
    }

    public function historyUpdate(string $log)
    {
        $this->history = array_merge($this->history??[],[$log. ', ' .date('d-m-Y h:i:s',strtotime('now'))]);
    }

    public function save(array $options = [])
    {
        if(!empty($this->form_data['full_name'])){
            $this->full_name = $this->form_data['full_name'];
        }else{
            if(!empty($this->form_data['surname'])){
                $this->full_name = trim(($this->form_data['surname']??'')
                    . ' ' . ($this->form_data['name']??'')
                    . ' ' . ($this->form_data['patronymic']??''));
            }else{
                $this->full_name = '';
            }
        }

        $this->phone = $this->form_data['phone'] ?? '';
        $this->email = $this->form_data['email'] ?? '';

        return parent::save($options);
    }

    public function updateFormData($field, $value)
    {
        $this->form_data = array_merge($this->form_data, [$field => $value]);
    }
    public function updateServiceData($field, $value)
    {
        if(!is_array($this->service_data)){
            $this->service_data = [];
        }
        $this->service_data = array_merge($this->service_data, [$field => $value]);
    }

    public function hasInServiceData(string $key): bool
    {
        return !empty($this->service_data[$key]);
    }

    public function changeRecipientsStatus($status){
        foreach ($this->recipients as $recipient) {
            $recipient->status = $status;
            $recipient->save();
        }
    }


    public function delete()
    {
        foreach ($this->recipients as $recipient) {
            $recipient->delete();
        }
        return parent::delete(); // TODO: Change the autogenerated stub
    }

}