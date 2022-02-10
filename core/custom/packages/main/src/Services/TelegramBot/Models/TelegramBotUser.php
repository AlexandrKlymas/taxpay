<?php
namespace EvolutionCMS\Main\Services\TelegramBot\Models;

use EvolutionCMS\Main\Services\FinesSearcher\Models\Fine;
use Illuminate\Database\Eloquent\Model;

/**
 * EvolutionCMS\Main\Services\TelegramBot\Models\BotUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUser query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $chat_id
 * @property string $command
 * @property array $temp_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUser whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUser whereCommand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUser whereTempData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramBotUser whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\EvolutionCMS\Main\Services\TelegramBot\Models\TelegramBotUserCar[] $cars
 * @property-read int|null $cars_count
 * @property-read Fine[] $fines
 */
class TelegramBotUser extends \Eloquent
{

    public static function getLastCommandByChatId($chatId){
//        return self::whereChatId($chatId)->first
    }

    protected $guarded = ['id'];

    protected $casts = [
        'temp_data'=>'array'
    ];

    public function setTempData($key,$value){
        $tempData = $this->temp_data;
        $tempData[$key] = $value;
        $this->temp_data = $tempData;
        $this->save();
    }

    public function fines(){
        return $this->belongsToMany(Fine::class)->using(FineTelegramBotUser::class)->withPivot('notify_new');
    }



    public function cars(){
        return $this->hasMany(TelegramBotUserCar::class);
    }


    public function setCommand($command){
        $this->command = $command;
        $this->save();
    }


}