<?php

namespace EvolutionCMS\Main\Services\TelegramBot\Models;


use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|FineTelegramBotUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FineTelegramBotUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FineTelegramBotUser query()
 * @property int $notify_new
 * @property-read Fine $fine
 * @property-read TelegramBotUser $telegramBotUser
 * @mixin \Eloquent
 */
class FineTelegramBotUser extends Pivot
{

    protected $guarded = ['*'];
    protected $casts = ['notify_new' => 'array'];


    public function fine(){
        return $this->belongsTo(Fine::class);
    }
    public function telegramBotUser(){
        return $this->belongsTo(TelegramBotUser::class);
    }

}