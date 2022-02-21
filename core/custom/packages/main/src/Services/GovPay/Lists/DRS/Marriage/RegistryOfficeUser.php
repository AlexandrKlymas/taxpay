<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DRS\Marriage;

use Illuminate\Database\Eloquent\Model;

class RegistryOfficeUser extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'telegram_id',
        'registry_office_id',
        'status',
    ];
}