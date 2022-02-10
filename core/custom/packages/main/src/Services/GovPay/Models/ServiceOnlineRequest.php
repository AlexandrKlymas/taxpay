<?php
namespace EvolutionCMS\Main\Services\GovPay\Models;


/**
 * EvolutionCMS\Main\Services\GovPay\Models\ServiceOnlineRequest
 *
 * @property int $id
 * @property string|null $formid
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $service
 * @property string|null $region
 * @property string|null $district
 * @property string|null $address
 * @property int $floor
 * @property int $rooms
 * @property int $pet
 * @property int $secure
 * @property string|null $date
 * @property int $status
 * @property string|null $href_doc
 * @property string $full_name
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereFormid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereHrefDoc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest wherePet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereRooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereSecure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceOnlineRequest whereStatus($value)
 * @mixin \Eloquent
 */
class ServiceOnlineRequest extends \Eloquent
{
    public $timestamps = false;
    protected $table = 'custom_zajavka';

    protected $fillable = [
        'formid','full_name','phone','email','service','region','district','address','floor','rooms','pet','secure','href_doc'
    ];
}