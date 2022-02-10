<?php


namespace EvolutionCMS\Main\Services\TelegramBot\Models;


use Illuminate\Database\Eloquent\Model;


/**
 * EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUserMessage
 *
 * @property int $id
 * @property int $telegram_bot_user_id
 * @property string $message_type
 * @property int $entity_id
 * @property int $message_id
 * @property string $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUser $telegramBotUser
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUserMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUserMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUserMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUserMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUserMessage whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUserMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUserMessage whereMessageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUserMessage whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUserMessage whereTelegramBotUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUserMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TelegramBotUserMessage extends Model
{

    protected $fillable = [
        'telegram_bot_user_id','message_type','entity_id','meta','message_id'
    ];

    protected $casts = [
        'meta'=>'array'
    ];

    const TYPE_FINE = 'fine';

    public function telegramBotUser(){
        return $this->belongsTo(TelegramBotUser::class,'telegram_bot_user_id','id');
    }

}