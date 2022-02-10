<?php
namespace EvolutionCMS\Main\Services\GovPay\Models;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\PoliceProtectionCode
 *
 * @property int $id
 * @property string $name
 * @property string $kod_o
 * @property string $kod_r
 * @property int $numfrom
 * @property int $numto
 * @property int $active
 * @property string $mfo
 * @property string $score
 * @property string $okpo
 * @property string $iban
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode whereKodO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode whereKodR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode whereMfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode whereNumfrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode whereNumto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode whereOkpo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PoliceProtectionCode whereScore($value)
 */

class PoliceProtectionCode extends \Eloquent
{
    public $timestamps = false;

    protected $table = 'poregcode_items';
}