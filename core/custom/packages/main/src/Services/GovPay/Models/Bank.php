<?php
namespace EvolutionCMS\Main\Services\GovPay\Models;



/**
 * EvolutionCMS\Main\Services\GovPay\Models\Bank
 *
 * @property int $id
 * @property string $name
 * @property int|null $mfo
 * @method static \Illuminate\Database\Eloquent\Builder|Bank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereMfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereName($value)
 * @mixin \Eloquent
 */
class Bank extends \Eloquent
{
    protected $table = 'banks_items';

}