<?php


namespace EvolutionCMS\Main\Services\CarInsurance\Models;


use Carbon\Carbon;

/**
 * EvolutionCMS\Main\Services\CarInsurance\Models\OsagoOrders
 *
 * @property int $id
 * @property string $insurance_params
 * @property int $status
 * @property string $payment_params
 * @property string $payment_callback
 */
class OsagoOrders extends \Eloquent
{
    protected $table = 'osago_orders';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'contract_id',
        'insurance_params',
        'status',
        'payment_callback',
        'contract_payment',
    ];

}