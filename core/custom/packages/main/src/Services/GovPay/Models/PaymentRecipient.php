<?php

namespace EvolutionCMS\Main\Services\GovPay\Models;

use Eloquent;
use EvolutionCMS\Main\Services\GovPay\Dto\PaymentRecipientDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient
 *
 * @property int $id
 * @property int $service_order_id
 * @property string $edrpou
 * @property string $account
 * @property string $mfo
 * @property float $amount
 * @property float $check_id
 * @property string|null $recipient_name
 * @property string|null $recipient_bank_name
 * @property string|null $purpose
 * @property string $recipient_type
 * @property string|null $service_name
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PaymentRecipient newModelQuery()
 * @method static Builder|PaymentRecipient newQuery()
 * @method static Builder|PaymentRecipient query()
 * @method static Builder|PaymentRecipient whereAccount($value)
 * @method static Builder|PaymentRecipient whereAmount($value)
 * @method static Builder|PaymentRecipient whereCheckId($value)
 * @method static Builder|PaymentRecipient whereCreatedAt($value)
 * @method static Builder|PaymentRecipient whereEdrpou($value)
 * @method static Builder|PaymentRecipient whereId($value)
 * @method static Builder|PaymentRecipient whereMfo($value)
 * @method static Builder|PaymentRecipient wherePurpose($value)
 * @method static Builder|PaymentRecipient whereRecipientBankName($value)
 * @method static Builder|PaymentRecipient whereRecipientName($value)
 * @method static Builder|PaymentRecipient whereRecipientType($value)
 * @method static Builder|PaymentRecipient whereServiceName($value)
 * @method static Builder|PaymentRecipient whereServiceOrderId($value)
 * @method static Builder|PaymentRecipient whereStatus($value)
 * @method static Builder|PaymentRecipient whereUpdatedAt($value)
 * @property-read ServiceOrder $serviceOrder
 * @mixin Eloquent
 */

class PaymentRecipient extends Eloquent
{

    const STATUS_WAIT = 'wait';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_FINISHED = 'finished';

    const RECIPIENT_TYPE_DIRECT = 'direct';

    const RECIPIENT_TYPE_MAIN = 'main';
    const RECIPIENT_TYPE_SYSTEM = 'system';

    const RECIPIENT_TK_COMMISSION = 'tk_commission';
    const RECIPIENT_GOVPAY_PROFIT = 'govpay_profit';

    protected $guarded = ['id'];

    protected $casts = [
        'amount'=>'float'
    ];

    public function serviceOrder(): BelongsTo
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public static function new(int $serviceOrderId, PaymentRecipientDto $paymentRecipientDto)
    {
        if(empty($paymentRecipientDto->getRecipientBankName())){
            $bank = BankItem::where('mfo',$paymentRecipientDto->getMfo())->first();
            $paymentRecipientDto->setRecipientBankName($bank->name??'');
        }

        return self::create([
            'service_order_id' => $serviceOrderId,
            'edrpou' =>  $paymentRecipientDto->getEdrpou(),
            'mfo' =>  $paymentRecipientDto->getMfo(),
            'account' =>  $paymentRecipientDto->getAccount(),
            'amount' =>  $paymentRecipientDto->getAmount(),
            'recipient_name' =>  $paymentRecipientDto->getRecipientName(),
            'recipient_bank_name' =>  $paymentRecipientDto->getRecipientBankName(),
            'purpose' =>  $paymentRecipientDto->getPurpose(),
            'recipient_type' =>  $paymentRecipientDto->getRecipientType(),
            'service_name' =>  $paymentRecipientDto->getServiceName(),

            'status' =>  PaymentRecipient::STATUS_WAIT,
            'check_id'=>self::generateUniqueCheck(),
        ]);
    }

    public static function generateUniqueCheck(): string
    {
        $rand = '';

        for($i=1;$i<=16;$i++){
            $rand .= rand(0,9);
        }

        if(self::where('check_id',$rand)->first()){
            $rand = self::generateUniqueCheck();
        }

        return $rand;
    }

    public static function getTypes(): array
    {
        return [
            self::RECIPIENT_TYPE_MAIN => 'Основний',
            self::RECIPIENT_TYPE_DIRECT => 'Прямий',
            self::RECIPIENT_TYPE_SYSTEM => 'Системний',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_WAIT => 'Очікує',
            self::STATUS_CONFIRMED => 'Підтверджений',
            self::STATUS_FINISHED => 'Завершений',
        ];
    }
    public function isFinished(): bool
    {
        return $this->status === self::STATUS_FINISHED;
    }

    /**
     * @return Collection|PaymentRecipient[]
     */
    public static function getConfirmedPayments()
    {
        return self::query()->where('status',self::STATUS_CONFIRMED)->get();
    }

    public function isMainRecipient(): bool
    {
        return $this->recipient_type === self::RECIPIENT_TYPE_MAIN;
    }

    public function canChangeStatusToFinished():bool
    {
        return in_array($this->status,[self::STATUS_CONFIRMED]);
    }

    public function changeStatusToFinished(){
        if(!$this->canChangeStatusToFinished()){
            throw new \DomainException('Can not change status to confirmed');
        }
        $this->status = self::STATUS_FINISHED;
        $this->save();
    }

    public function changeStatusToConfirmed(){
        if(!$this->canChangeStatusToConfirmed()){
            throw new \DomainException('Can not change status to confirmed');
        }

        $this->status = self::STATUS_CONFIRMED;
        $this->save();
    }

    public function canChangeStatusToConfirmed():bool
    {
        return $this->status === self::STATUS_WAIT;
    }
}