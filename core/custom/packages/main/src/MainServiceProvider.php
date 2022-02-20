<?php

namespace EvolutionCMS\Main;


use EvolutionCMS\Main\Console\Bank\BankExportCommand;
use EvolutionCMS\Main\Console\Bank\BankImportCommand;
use EvolutionCMS\Main\Console\CronCommand;
use EvolutionCMS\Main\Console\EchoCommand;
use EvolutionCMS\Main\Console\ServiceOrders\FinishServiceOrdersCommand;
use EvolutionCMS\Main\Console\ServiceOrders\ParseFinesCommand;
use EvolutionCMS\Main\Console\Support\FixCommissionsCommand;
use EvolutionCMS\Main\Console\Support\ServiceCommand;
use EvolutionCMS\Main\Services\GovPay\Contracts\IFinesApi;
use EvolutionCMS\Main\Services\FinesSearcher\Api\FakeFinesApi;
use EvolutionCMS\Main\Services\FinesSearcher\Api\InfoTechFinesApi;
use EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax\SudyTaxCallbackCheckCommand;
use EvolutionCMS\Main\Services\GovPay\Lists\SVU\SudyTax\SudyTaxCallbackCustomServiceCommand;
use EvolutionCMS\Main\Services\TelegramBot\Console\SearchFinesCommand;
use EvolutionCMS\Main\Services\TelegramBot\Console\SendFinesNotificationCommand;
use EvolutionCMS\Main\Services\TelegramBot\Console\SetWebHookCommand;
use EvolutionCMS\ServiceProvider;
use Exception;
use TelegramBot\Api\BotApi;

class MainServiceProvider extends ServiceProvider
{

    /**
     * Если указать пустую строку, то сниппеты и чанки будут иметь привычное нам именование
     * Допустим, файл test создаст чанк/сниппет с именем test
     * Если же указан namespace то файл test создаст чанк/сниппет с именем main#test
     * При этом поддерживаются файлы в подпапках. Т.е. файл test из папки subdir создаст элемент с именем subdir/test
     */
    protected string $namespace = 'main';

    protected array $commands = [

        EchoCommand::class,
        CronCommand::class,

        SearchFinesCommand::class,
        SendFinesNotificationCommand::class,
        SetWebHookCommand::class,

        BankExportCommand::class,
        BankImportCommand::class,

        FinishServiceOrdersCommand::class,

        ParseFinesCommand::class,

        SudyTaxCallbackCheckCommand::class,
        SudyTaxCallbackCustomServiceCommand::class,
        FixCommissionsCommand::class,
        ServiceCommand::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     * @throws Exception
     */
    public function register()
    {

        if(is_cli()){
           chdir(evo()->getConfig('site_path'));
        }

        $this->preInit();
        $this->loadPluginsFrom(
            dirname(__DIR__) . '/plugins/'
        );

        $this->commands($this->commands);

        $this->app->registerModule('Таблица платежей', dirname(__DIR__) . '/modules/Orders/Orders.php');
        $this->app->registerModule('Таблица платежей Архив', dirname(__DIR__) . '/modules/OrdersArchive/Orders.php');

        $this->loadViewsFrom(__DIR__ . '/../modules/Orders/views/', 'Modules.Orders');
        $this->loadViewsFrom(__DIR__ . '/../modules/OrdersArchive/views/', 'Modules.OrdersArchive');
        $this->loadViewsFrom(__DIR__ . '/../modules/FineSearchHistory/views/', 'Modules.FineSearchHistory');

        if (evo()->getConfig('dev') === true) {
            $this->app->singleton(IFinesApi::class, FakeFinesApi::class);
        } else {
            $this->app->singleton(IFinesApi::class, InfoTechFinesApi::class);
        }

        $this->app->singleton(BotApi::class,function (){
            return new BotApi(evo()->getConfig('main_telegramBotToken'));
        });


        $this->registerModules();

    }

    private function registerModules()
    {
        $this->app->registerModule(
            'Коды ПО',
            dirname(__DIR__) . '/modules/WebixTableModule.php',
            '',
            [
                'module_alias' => 'poregcode',

                'name' => 'Коды ПО: Список кодов полиции охраны',
                'fields' => 'id, name, kod_o, kod_r, numfrom, numto, iban, okpo',
                'fields_for_popup_editor' => '',
                'fields_names' => 'ID,Название,Код области, Код счета, Номера от, Номера до, IBAN, ОКПО',
                'idField' => 'id',
                'table' => 'poregcode_items',
                'display' => '30',
            ]
        );

        $this->app->registerModule(
            'Комиссии',
            dirname(__DIR__) . '/modules/WebixTableModule.php',
            '',
            [
                'module_alias' => 'commissions',

                'name' => 'Управление комиссиями',
                'fields' => 'id, form_id, percent, min_summ, max_summ, fix_summ',
                'fields_for_popup_editor' => '',
                'fields_names' => 'ID, ID формы, Процент, Мин, Макс, Фикс',
                'idField' => 'id',
                'table' => 'table_komissions',
                'display' => '15',
            ]
        );

        $this->app->registerModule(
            'Реквизиты монтажных услуг ПО',
            dirname(__DIR__) . '/modules/WebixTableModule.php',
            '',
            [
                'module_alias' => 'montcode_items',

                'name' => 'Реквизиты монтажных услуг ПО',
                'fields' => 'id,name_ua, description, iban, okpo, active',
                'fields_for_popup_editor' => '',
                'fields_names' => 'ID,Название (ua), Наименование банка, IBAN, ОКПО, Активен',
                'idField' => 'id',
                'table' => 'montcode_items',
                'display' => '30',
            ]
        );
        $this->app->registerModule(
            'Список поступивших заявок',
            dirname(__DIR__) . '/modules/WebixTableModule.php',
            '',
            [
                'module_alias' => 'service_online_request',

                'name' => 'Список поступивших заявок',
                'fields' => 'id, formid, full_name, phone, email, service, region, district, address, floor, rooms, pet, secure, date, status,href_doc',
                'fields_for_popup_editor' => 'href_doc',
                'fields_names' => 'ID, Форма, фио, Телефон, Email, Услуга, Область, Район, Адрес, Этаж, Комнат, Животные, Охрана, Дата, Статус, PDF',
                'idField' => 'id',
                'table' => 'custom_zajavka',
                'display' => '20',
                'tpl' => 'zajavka',
            ]
        );

        $this->app->registerModule(
            'Реквізити рахунків РСЦ МВС України',
            dirname(__DIR__) . '/modules/WebixTableModule.php',
            '',
            [
                'module_alias' => 'regionalServiceCenter',

                'name' => 'Реквізити рахунків РСЦ МВС України (Обрати регіон)',
                'fields' => 'id, name_ua, iban, egrpou',
                'fields_for_popup_editor' => '',
                'fields_names' => 'ID, Область (ua), IBAN, ЕГРПОУ',
                'idField' => 'id',
                'table' => 'table_poluch5',
                'display' => '30'
            ]
        );


        $this->app->registerModule(
            'РСЦ при МВС України',
            dirname(__DIR__) . '/modules/WebixTableModule.php',
            '',
            [
                'module_alias' => 'TerritorialServiceCenter',

                'name' => 'РСЦ при МВС України (РСЦ вашої області)',
                'fields' => 'id, code, iban, egrpou, region_id, name_ua, add_code',
                'fields_for_popup_editor' => '',
                'fields_names' => 'ID, Код подразд, IBAN, ЕГРПОУ, ID региона, Новое название (ua),Подкод',
                'idField' => 'id',
                'table' => 'table_poluch6',
                'display' => '30'
            ]
        );


        $this->app->registerModule(
            'Услуги',
            dirname(__DIR__) . '/modules/WebixTableModule.php',
            '',
            [
                'module_alias' => 'services',

                'name' => 'Список платных услуг. Типы: 99 - допуслуги; 7 - бесплатные в заявке; остальные типы задаются в формах',
                'fields' => 'id, type, code, name_ua, price',
                'fields_for_popup_editor' => '',
                'fields_names' => 'ID, Тип, Код, Название (ua), Цена',
                'idField' => 'id',
                'table' => 'services',
                'display' => '30'
            ]
        );
        $this->app->registerModule(
            'Реквизиты счетов регионов: ПДД',
            dirname(__DIR__) . '/modules/WebixTableModule.php',
            '',
            [
                'module_alias' => 'PencodesItems',

                'name' => 'Реквизиты счетов регионов: ПДД',
                'fields' => 'id, name_ua, region, description, iban, okpo, active',
                'fields_for_popup_editor' => '',
                'fields_names' => 'ID,Название (ua), Регион, Получатель, IBAN, ОКПО, Активен',
                'idField' => 'id',
                'table' => 'pencodes_items',
                'display' => '30'
            ]
        );

        $this->app->registerModule(
            'Реквизиты счетов регионов: Виконавча Влада',
            dirname(__DIR__) . '/modules/WebixTableModule.php',
            '',
            [
                'module_alias' => 'VVPayDetails',

                'name' => 'Реквизиты счетов регионов: Виконавча Влада',
                'fields' => 'id, region, district, recipient, iban, mfo, edrpou, active',
                'fields_for_popup_editor' => '',
                'fields_names' => 'ID,Область чи місто, Районний відділ, Одержувач (установа), IBAN, МФО, Єдрпоу, Активний',
                'idField' => 'id',
                'table' => 'vvpay_details',
                'display' => '30'
            ]
        );

        $this->app->registerModule(
            'Реквизиты счетов регионов: Парковка',
            dirname(__DIR__) . '/modules/WebixTableModule.php',
            '',
            [
                'module_alias' => 'ParkPencodeItems',

                'name' => 'Реквизиты счетов регионов: Парковка',
                'fields' => 'id, name_ua, region, description, iban, okpo, active',
                'fields_for_popup_editor' => '',
                'fields_names' => 'ID,Название (ua), Регион, Получатель, IBAN, ОКПО, Активен',
                'idField' => 'id',
                'table' => 'park_pencode_items',
                'display' => '30'
            ]
        );


        $this->app->registerModule(
            'История поиска штрафов',
            dirname(__DIR__) . '/modules/FineSearchHistory/FineSearchHistory.php',
            '',
            [
                'module_alias' => 'FineSearchHistory',
            ]
        );


        $this->app->registerModule(
            'Медицинские учереждения',
            dirname(__DIR__) . '/modules/medcenters/med.module.php',
            '',

        );

        $this->app->registerModule(
            'РАЦСи',
            dirname(__DIR__) . '/modules/registry_offices/registry_offices.module.php',
            '',
        );

        $this->app->registerModule(
            'Сервисные комиссии',
            dirname(__DIR__) . '/modules/commissions/commissions.module.php',
            '',
        );

    }

    private function preInit()
    {
        if (empty($_SERVER['HTTP_HOST'])) {
            $_SERVER['HTTPS'] = evo()->getConfig('main_site_https');
            $_SERVER['HTTP_HOST'] = evo()->getConfig('main_site_url');
            $scheme = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
            evo()->setConfig('site_url', $scheme . '://' . evo()->getConfig('main_site_url') . '/');
        }
        if (empty($_SESSION['REQUEST_TIME'])) {
            $_SERVER['REQUEST_TIME'] = time();
        }

        evolutionCMS()->db->connect();
    }
}