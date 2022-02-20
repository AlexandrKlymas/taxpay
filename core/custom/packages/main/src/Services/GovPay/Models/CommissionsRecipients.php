<?php

namespace EvolutionCMS\Main\Services\GovPay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * EvolutionCMS\Main\Services\GovPay\Models\CommissionsRecipients
 *
 * @property int $id
 * @property string $recipient_name
 * @property int $edrpou
 * @property string $mfo
 * @property string $iban
 * @property string $purpose_template
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 **/

class CommissionsRecipients extends Model
{
    protected $table = 'commissions_recipients';

    protected $fillable = [
        'recipient_name',
        'edrpou',
        'mfo',
        'iban',
        'purpose_template',
    ];
}