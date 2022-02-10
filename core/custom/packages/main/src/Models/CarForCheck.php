<?php

namespace EvolutionCMS\Main\Models;

use Illuminate\Database\Eloquent;

class CarForCheck extends Eloquent\Model
{
    protected $table = 'car_for_check';

    protected $fillable = [
        'car_for_check_id',
        'hash',
        'status'
    ];
}