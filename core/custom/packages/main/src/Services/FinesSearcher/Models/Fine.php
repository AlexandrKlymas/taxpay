<?php


namespace EvolutionCMS\Main\Services\FinesSearcher\Models;

use EvolutionCMS\Main\Services\GovPay\Contracts\IFinesApi;
use EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser;
use EvolutionCMS\Main\Support\Helpers;
use Illuminate\Support\Carbon;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\Fine
 *
 * @property int $id
 * @property int $fine_doc_id
 * @property string $protocol_series
 * @property string $protocol_number
 * @property int|null $telegram_bot_user_id
 * @property string $command
 * @property array $command_info
 * @property array $data
 * @property bool $paid
 * @property array $notify
 * @property TelegramBotUser $telegramAccount
 * @property TelegramBotUser[] $telegramBotUsers
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $dsignPost
 * @property \Illuminate\Support\Carbon|null $d_perpetration
 * @method static \Illuminate\Database\Eloquent\Builder|Fine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Fine query()
 * @method static \Illuminate\Database\Eloquent\Builder|Fine whereCommand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fine whereCommandInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fine whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fine whereFineDocId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fine whereNotify($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fine wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fine whereTelegramBotUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Fine whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Fine extends \Eloquent
{
    private $finesApi;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->finesApi = evolutionCMS()->make(IFinesApi::class);
    }

    protected $casts = [
        'command_info' => 'array',
        'data' => 'array',
        'notify' => 'array',
        'paid' => 'bool',
    ];
    protected $guarded = ['id'];

    public function getCrimePlace()
    {
        $place = [];
        if (!empty($this->data['region'])) {
            $place[] = 'регіон ' . $this->data['region'];
        }
        if (!empty($this->data['district'])) {
            $place[] = 'район ' . $this->data['district'];
        }

        if (!empty($this->data['city'])) {
            $place[] = 'місто ' . $this->data['city'];
        }
        if (!empty($this->data['street'])) {
            $place[] = 'вулиця ' . $this->data['street'];
        }
        if (!empty($this->data['region'])) {
            $place[] = 'р. ' . $this->data['region'];
        }
        if (!empty($this->data['roadKm'])) {
            $place[] = 'кілометр ' . $this->data['roadKm'];
        }
        return implode(', ', $place);
    }

    public function getType()
    {
        if ($this->data['typeFine'] == 1) {
            return 'Звичайна';
        }
        return 'фотофіксація';
    }

    public function getSeries()
    {
        return $this->data['sprotocol'];
    }

    public function getSum()
    {
        return $this->data['sumPenalty'];
    }

    public function getNumber()
    {
        return $this->data['nprotocol'];
    }

    public function getFineDate(): Carbon
    {
        return (Carbon::createFromTimestamp(strtotime($this->data['dperpetration'])));
    }

    public function getDescription()
    {
        $description = '';
        if (!empty($this->data['fab'])) {
            $description = $this->data['fab'];
        } elseif (!empty($this->data['kupap'])) {
            $description = $this->data['kupap'];;
        }
        $description = Helpers::mbUcfirst(mb_strtolower($description, 'utf-8'));

        return $description;
    }


    public function isPaid()
    {

        return $this->paid;
    }

    public function isPaymentConfirmed()
    {

        if ($this->data['send'] === 'НЕ НАПРАВЛЯВСЯ' && !empty($this->data['dpaid'])) {
            return true;
        } else {
            return false;
        }
    }

    public function isSendTo()
    {
        return $this->data['send'] !== 'НЕ НАПРАВЛЯВСЯ';
    }

    public function getStatus()
    {
        $status = 'not-paid';
        if ($this->isPaymentConfirmed()) {
            $status = 'payment-confirmed';
        } else if ($this->isPaid()) {
            $status = 'paid';
        } elseif ($this->isSendTo()) {
            $status = 'send-to';
        }
        return $status;
    }


    public function getStatusTitle()
    {

        $title = '';
        switch ($this->getStatus()) {
            case 'not-paid':
                $title = 'Не сплачено';
                break;
            case  'paid':
                $title = 'Сплачено';
                break;
            case  'payment-confirmed':
                $title = 'Сплачено | ПОГАШЕНО в базі Патрульної поліції';
                break;
            case  'send-to':
                $title = 'Направлено в "' . $this->data['send'] . '"';
                break;
        }
        return $title;
    }


    public function canBePaid()
    {

        return
            $this->isSendTo() === false &&
            $this->isPaid() === false &&
            $this->isPaymentConfirmed() === false &&
            !empty($this->data['paidinfo']);
    }

    public function getNotify($key)
    {
        return $this->notify[$key] ?? $this->notify[$key];
    }


    public function setNotify($key, $value)
    {
        $upd = $this->notify;
        $upd[$key] = $value;
        $this->notify = $upd;
    }

    public function telegramBotUsers()
    {
        return $this->belongsToMany(TelegramBotUser::class);
    }

    public function save(array $options = [])
    {
        return parent::save($options);
    }
}