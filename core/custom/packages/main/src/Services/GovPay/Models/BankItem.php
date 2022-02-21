<?php

namespace EvolutionCMS\Main\Services\GovPay\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\BankItem
 *
 * @property int $id
 * @property string $name
 * @property string $mfo
 **/

class BankItem extends Model
{
    protected $table = 'banks_items';

    protected $fillable = [
        'name',
        'mfo',
    ];
}