<?php


namespace EvolutionCMS\Main\Services\CarInsurance\Models;


use Carbon\Carbon;

/**
 * EvolutionCMS\Main\Services\CarInsurance\Models\OsagoModel
 *
 * @property int $id
 * @property string $markId
 * @property string $modelId
 * @property string $modelName
 */
class OsagoModel extends \Eloquent
{
    protected $table = 'osago_model';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'markId',
        'modelId',
        'modelName',
    ];

}