<?php

namespace EvolutionCMS\Main\Services\GovPay\Models;


use Illuminate\Database\Eloquent\Builder;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\VvpayDetails
 *
 * @property int $id
 * @property string|null $region
 * @property string|null $district
 * @property string|null $recipient
 * @property string|null $iban
 * @property string|null $mfo
 * @property string|null $edrpou
 * @property bool|null   $active
 * @method static Builder|VvpayDetails newModelQuery()
 * @method static Builder|VvpayDetails newQuery()
 * @method static Builder|VvpayDetails query()
 * @method static Builder|VvpayDetails whereId($value)
 * @method static Builder|VvpayDetails whereRegion($value)
 * @method static Builder|VvpayDetails whereDistrict($value)
 * @method static Builder|VvpayDetails whereRecipient($value)
 * @method static Builder|VvpayDetails whereIban($value)
 * @method static Builder|VvpayDetails whereMfo($value)
 * @method static Builder|VvpayDetails whereEdrpou($value)
 * @method static Builder|VvpayDetails whereActive($value)
 * @mixin \Eloquent
 */
class VvpayDetails extends \Eloquent
{

}