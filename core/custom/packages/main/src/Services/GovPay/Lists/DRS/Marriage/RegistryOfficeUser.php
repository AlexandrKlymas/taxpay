<?php

namespace EvolutionCMS\Main\Models;

use Illuminate\Database\Eloquent\Model;

class RegistryOfficeUser extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'telegram_id',
        'registry_office_id',
        'status',
    ];
}