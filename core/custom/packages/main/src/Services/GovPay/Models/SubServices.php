<?php

namespace EvolutionCMS\Main\Services\GovPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\SubServices
 *
 * @property int $id
 * @property int $service_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 **/

class SubServices extends Model
{
    protected $table = 'sub_services';

    protected $fillable = [
      'name',
      'service_id'
    ];
}