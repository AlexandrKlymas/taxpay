<?php

namespace EvolutionCMS\Main\Services\GovPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\ServiceRecipient
 *
 * @property int $id
 * @property int $service_id
 * @property int $sub_service_id
 * @property string $recipient_name
 * @property int $edrpou
 * @property string $mfo
 * @property string $iban
 * @property string $purpose_template
 * @property float $sum
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 **/

class ServiceRecipient extends Model
{
    protected $table = 'service_recipients';

    protected $fillable = [
        'service_id',
        'sub_service_id',
        'recipient_name',
        'edrpou',
        'mfo',
        'iban',
        'purpose_template',
        'sum',
    ];
}