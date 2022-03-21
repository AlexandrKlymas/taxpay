<?php
namespace EvolutionCMS\Main\Console;

use GO\Scheduler;
use Illuminate\Console\Command;

class CronCommand extends Command
{
    protected $signature = 'cron-start';

    protected $description = 'Start cron schedule';


    public function handle(){
        $phpPath = evo()->getConfig('phpPath');

        $corePath = EVO_CORE_PATH;
        $scheduler = new Scheduler();

//        evo()->logEvent(1,1,1,'cronLog');
 

//        $scheduler->raw("$phpPath $corePath/artisan bot:search-fines")->at('0,20,40 * * * *');
//        $scheduler->raw("$phpPath $corePath/artisan bot:notification-send")->at('5,25,45 * * * *');


        $scheduler->raw("$phpPath $corePath/artisan bank:export")->at('55 16 * * 1-5');
//        Создаем проводки для банка и отправляем ему на фтп (будние 09:05-16:05 каждый час) было (будние 09:05-14:05 каждый час)
        $scheduler->raw("$phpPath $corePath/artisan bank:export")->at('6,36 9-17 * * 1-6');



        $scheduler->raw("$phpPath $corePath/artisan bank:import")->at('2,32 * * * *');
        $scheduler->raw("$phpPath $corePath/artisan bank:import --day-type=yesterday")->at('0 1 * * *');

        $scheduler->raw("$phpPath $corePath/artisan service_orders:finish")->at('*/15 * * * *');

//        $scheduler->raw("$phpPath $corePath/artisan parse:fines")->at('*/5 * * * *');

        $scheduler->raw("$phpPath $corePath/artisan sudytax:findcallbacks")->at('22 * * * *');


        //запуска воркера очередей, каждую минуту
        $queueWorkerStartCommand = "$phpPath $corePath/artisan queue:work --stop-when-empty";
        if(evo()->getConfig('dev') === true){
            $queueWorkerStartCommand .= " --force";
        }

        $scheduler->raw($queueWorkerStartCommand)->at('* * * * *');


        //Создаем проводки для банка и отправляем ему на фтп (будние в 16:55) было (будние в 15:55)
        $scheduler->run();

    }
}