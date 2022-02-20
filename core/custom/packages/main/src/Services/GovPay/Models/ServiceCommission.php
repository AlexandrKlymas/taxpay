<?php

namespace EvolutionCMS\Main\Services\GovPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\ServiceCommission
 *
 * @property int $id
 * @property int $service_recipient_id
 * @property int $commissions_recipient_id
 * @property float $percent
 * @property float $min
 * @property float $max
 * @property float $fix
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 **/

class ServiceCommission extends Model
{
    protected $table = 'service_commissions';

    protected $fillable = [
        'service_recipient_id',
        'commissions_recipient_id',
        'percent',
        'min',
        'max',
        'fix',
    ];
}