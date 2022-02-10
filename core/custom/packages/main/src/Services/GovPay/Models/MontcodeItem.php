<?php
namespace EvolutionCMS\Main\Services\GovPay\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\MontcodeItem
 *
 * @property int $id
 * @property string|null $name_ua
 * @property string|null $name_ru
 * @property string|null $description
 * @property string|null $mfo
 * @property string|null $score
 * @property string|null $okpo
 * @property bool $active
 * @property string $iban
 * @method static \Illuminate\Database\Eloquent\Builder|MontcodeItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MontcodeItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MontcodeItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|MontcodeItem whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MontcodeItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MontcodeItem whereIban($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MontcodeItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MontcodeItem whereMfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MontcodeItem whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MontcodeItem whereNameUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MontcodeItem whereOkpo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MontcodeItem whereScore($value)
 * @mixin \Eloquent
 */
class MontcodeItem extends Model
{

}