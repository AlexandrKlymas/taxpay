<?php


namespace EvolutionCMS\Main\Services\CarInsurance\Models;


use Carbon\Carbon;

/**
 * EvolutionCMS\Main\Services\CarInsurance\Models\OsagoMarka
 *
 * @property int $id
 * @property string $markId
 * @property string $markName
 */
class OsagoMarka extends \Eloquent
{
    protected $table = 'osago_marka';
    public $timestamps = false;

    
    protected $fillable = [
        'id',
        'markId',
        'markName',
    ];

}