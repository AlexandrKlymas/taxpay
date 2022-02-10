<?php


namespace EvolutionCMS\Main\Services\CarInsurance\Models;


use Carbon\Carbon;

/**
 * EvolutionCMS\Main\Services\CarInsurance\Models\OsagoPaymentInfo
 *
 * @property int $id
 *
 * @property int $form_id
 * @property int $doc_id
 *
 * @property string $fio
 * @property string $address
 * @property string $phone
 * @property string $email
 *
 * @property string $post_info
 * @property string $vis_table
 * @property string $payment_order
 * @property string $payment_order_fields
 *
 * @property string $poluch_name
 * @property string $poluch_account
 * @property string $poluch_egrpou
 * @property string $poluch_mfo
 * @property string $poluch_bank
 * @property string $pagetitle
 *
 * @property float $summ_original
 * @property float $summ_komission
 * @property float $itogo
 * @property float $summ_liqpey
 * @property float $summ_bank
 * @property float $summ_ostatok
 *
 * @property  $date
 * @property  $status_date
 *
 * @property  string $status
 *
 * @property int $transaction_id
 * @property int $payment_id
 *
 * @property  $create_date
 * @property  $end_date
 *
 * @property string $liqpay_responce
 *
 * @property float $receiver_commission
 *
 * @property string $href_order_pdf
 * @property string $user_geo_data
 * @property string $policyDirectLink
 * @property string $contractId
 *
 */
class OsagoPaymentInfo extends \Eloquent
{
    protected $table = 'osago_payment_info';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'form_id',
        'doc_id',
        'fio',
        'address',
        'phone',
        'email',
        'post_info',
        'vis_table',
        'payment_order',
        'payment_order_fields',
        'poluch_name',
        'poluch_account',
        'poluch_egrpou',
        'poluch_mfo',
        'poluch_bank',
        'pagetitle',
        'summ_original',
        'summ_komission',
        'itogo',
        'summ_liqpey',
        'summ_bank',
        'summ_ostatok',
        'date',
        'status_date',
        'status',
        'transaction_id',
        'payment_id',
        'create_date',
        'end_date',
        'liqpay_responce',
        'receiver_commission',
        'href_order_pdf',
        'user_geo_data',
        'policyDirectLink',
        'contractId',
    ];

}