<?php

namespace EvolutionCMS\Main\Services\GovPay\Models;


/**
 * EvolutionCMS\Main\Services\GovPay\Models\RegionalServiceCenter
 *
 * @property int $id
 * @property string|null $name_ua
 * @property string|null $name_ru
 * @property string|null $account
 * @property string|null $egrpou
 * @property string|null $mfo
 * @property string $iban
 * @method static \Illuminate\Database\Eloquent\Builder|RegionalServiceCenter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegionalServiceCenter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegionalServiceCenter query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegionalServiceCenter whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionalServiceCenter whereEgrpou($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionalServiceCenter whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionalServiceCenter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionalServiceCenter whereMfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionalServiceCenter whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionalServiceCenter whereNameUa($value)
 * @mixin \Eloquent
 */
class RegionalServiceCenter extends \Eloquent
{
    protected $table = 'table_poluch5';
}