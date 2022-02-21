<?php

namespace EvolutionCMS\Main\Services\GovPay\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\Commission
 *
 * @property $percent
 * @property $min_summ
 * @property $max_summ
 * @property $fix_summ
 * @mixin Eloquent
 * @property int $id
 * @property int $form_id
 * @method static Builder|Commission newModelQuery()
 * @method static Builder|Commission newQuery()
 * @method static Builder|Commission query()
 * @method static Builder|Commission whereFixSumm($value)
 * @method static Builder|Commission whereFormId($value)
 * @method static Builder|Commission whereId($value)
 * @method static Builder|Commission whereMaxSumm($value)
 * @method static Builder|Commission whereMinSumm($value)
 * @method static Builder|Commission wherePercent($value)
 */

class Commission extends Eloquent
{
    protected $table = 'table_komissions';
}