<?php

namespace EvolutionCMS\Main\Models;

use Illuminate\Database\Eloquent;

class MedicalCenterUser extends Eloquent\Model
{
    protected $fillable = [
        'name',
        'phone',
        'telegram_id',
        'medical_center_id',
        'status',
    ];
}