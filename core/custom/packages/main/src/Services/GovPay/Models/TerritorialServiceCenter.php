<?php


namespace EvolutionCMS\Main\Services\GovPay\Models;


/**
 * EvolutionCMS\Main\Services\GovPay\Models\TerritorialServiceCenter
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $account
 * @property string|null $egrpou
 * @property string|null $name_ua
 * @property string|null $name_ru
 * @property string|null $add_code
 * @property int|null $region_id
 * @property int|null $pens_id
 * @property string $iban
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter query()
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter whereAddCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter whereEgrpou($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter whereNameUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter wherePensId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TerritorialServiceCenter whereRegionId($value)
 * @mixin \Eloquent
 */
class TerritorialServiceCenter extends \Eloquent
{
    protected $table = 'table_poluch6';
}