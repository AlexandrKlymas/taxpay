<?php
namespace EvolutionCMS\Main\Services\GovPay\Models;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\Commission
 *
 * @property $percent
 * @property $min_summ
 * @property $max_summ
 * @property $fix_summ
 * @mixin \Eloquent
 * @property int $id
 * @property int $form_id
 * @method static \Illuminate\Database\Eloquent\Builder|Commission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Commission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Commission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereFixSumm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereMaxSumm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission whereMinSumm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Commission wherePercent($value)
 */

class Commission extends \Eloquent
{
    protected $table = 'table_komissions';
}