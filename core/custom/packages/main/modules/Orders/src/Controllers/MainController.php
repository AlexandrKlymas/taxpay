<?php

namespace EvolutionCMS\Main\Modules\Orders\Controllers;

use EvolutionCMS\Main\Modules\Orders\OrdersModuleHelper;
use EvolutionCMS\Main\Services\GovPay\Managers\StatusManager;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Main\Support\Helpers;
use EvolutionCMS\Models\SiteContent;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use View;

class MainController extends BaseController
{
    /**
     * @var OrdersModuleHelper
     */
    private OrdersModuleHelper $ordersModuleHelper;

    public function __construct()
    {
        parent::__construct();

        $this->ordersModuleHelper = new OrdersModuleHelper();
    }

    public function index(Request $request)
    {
        $serviceCustomTitles = [
            26 => 'Поліція охорони',
            47 => 'Штрафи',
            145 => 'Штрафи по постанові',
            49 => 'Монтажні послуги',
            51 => 'Експертна служба',
            55 => 'Реєстрація-перереєстрація ТЗ',
            57 => 'Реєстрація нових ТЗ',
            61 => 'Зберігання ДНЗ',
            62 => 'Номерні знаки 0001-9999',
            63 => 'Водійські посвідчення',
            162 => 'Судовий збір',
            165 => 'Податковий збір',
            170 => 'ПЛР-тест',
        ];
        $services = SiteContent::active()->whereIn('template', [5,11,16,17,18,19,20])->get()->toArray();

        $serviceList = [];
        foreach ($services as $service) {
            $serviceList[] = [
                'id'=>$service['id'],
                'value'=>isset($serviceCustomTitles[$service['id']])?$serviceCustomTitles[$service['id']]:$service['pagetitle'],
            ];
        }

        $statuses = (new StatusManager())->getStatuses();
        $sortStatuses = [];

        $j = 0;
        foreach ($statuses as $key => $value) {
            $sortStatuses[] = [
                'id' => $key,
                'value' => $value,
                'index' => $j
            ];
            $j++;
        }

        $data = [
            'services' => $serviceList,
            'statuses' => $sortStatuses,
            'recipient_statuses' => Helpers::arrayTransformoWebixOptions(PaymentRecipient::getStatuses()),
            'recipient_types' => Helpers::arrayTransformoWebixOptions(PaymentRecipient::getTypes()),
        ];

        return View::make('Modules.Orders::index', array_merge($this->viewData, $data))->render();
    }

    public function prepareData(ServiceOrder $order): array
    {
        return [
            'id' => $order->id,
            'time' => microtime(),
            'service_id' => $order->service_id,
            'recipient_name' => $order->recipient_name,
            'status' => $order->status,
            'liqpay_status' => $order->liqpay_status,
            'liqpay_transaction_id' => $order->liqpay_transaction_id,
            'liqpay_payment_date' => $order->liqpay_payment_date ? $order->liqpay_payment_date->format('d-m-Y H:i:s') : '',

            'full_name' => $order->full_name,
            'phone' => $order->phone,
            'email' => $order->email,

            'total' => $order->total,
            'sum' => $order->sum,
            'liqpay_real_commission' => $order->liqpay_real_commission,
            'bank_commission' => $order->bank_commission,
            'profit' => $order->profit,

            'invoice_file_pdf' => $order->invoice_file_pdf
        ];
    }

    public function loadOrder(Request $request): array
    {
        $q = ServiceOrder::where('service_orders.id',$request->get('orderId'));

        $q->select(['service_orders.*','payment_recipients.recipient_name']);
        $q->join('payment_recipients',function ($join){
            $join->on('service_orders.id','=','service_order_id')
                ->whereIn('recipient_type',['main','direct'])
                ->limit(1);
        });

        /** @var ServiceOrder $serviceOrder */
        $serviceOrder = $q->first();

        $data = $this->prepareData($serviceOrder);

        return [
            'status' => 'success',
            'data' => $data
        ];
    }

    public function loadOrders(Request $request): array
    {
        $perPage = 15;
        $page = 1;

        if ($_GET['start']) {
            $page = (int)$_GET['start'] / $perPage + 1;
        }

        $q = ServiceOrder::query();

        $q->select(['service_orders.*','payment_recipients.recipient_name']);
        $q->join('payment_recipients',function ($join){
            $join->on('service_orders.id','=','service_order_id')
                ->whereIn('recipient_type',['main','direct'])
                ->limit(1);
        });

//        $archive = ServiceOrder::select(['id'])
//            ->whereIn('service_orders.status',['wait','error','failure'])
//            ->where('service_orders.created_at','<',date('Y-m-d H:i:s',strtotime('-1 day')))
//            ->get()
//        ;
//
//        $q->whereNotIn('service_orders.id', array_column($archive->toArray(),'id')??[]);

        $q = $this->ordersModuleHelper->insertFilterRulesToQuery($request, $q);
        $q = $this->ordersModuleHelper->insertSortRulesToQuery($request, $q);

        $q->orderBy('service_orders.id', 'desc');
        $orderPaginate = $q->paginate($perPage, $columns = ['*'], $pageName = 'page', $page);
        /** @var ServiceOrder[] $orders */
        $orders = $orderPaginate->items();

        $data = [];
        foreach ($orders as $order) {

            $data[] = $this->prepareData($order);
        }

        $response = [
            'data' => $data,
        ];

        if (empty($_GET['start'])) {
            $response['total_count'] = $orderPaginate->total();
        } else {
            $response['pos'] = $_GET['start'];
        }

        return $response;
    }

    public function exportToExcel(Request $request)
    {
        $ids = [];
        if ($request->get('checked')) {
            $ids = explode(',', $request->get('checked'));
        }
        $q = ServiceOrder::query();

        if ($ids) {
            $q->whereIn('id', $ids);
        } else {
            $q = $this->ordersModuleHelper->insertFilterRulesToQuery($request, $q);
        }

        $q = $this->ordersModuleHelper->insertSortRulesToQuery($request, $q);

        $q->with('mainRecipients');

        $orders = $q->get();

        $data = [
            [
                'id', 'Форма', 'Получатель', 'Статус', 'Дата оплаты', 'ФИО', 'телефон', 'Email', 'Транзакция', 'Оплата', 'LiqPay', 'TK', 'GP'
            ]
        ];

        $serviceTitles = SiteContent::where('template', 5)->get()->pluck('pagetitle', 'id');

        /** @var ServiceOrder[] $orders */
        foreach ($orders as $order) {

            $firstMainRecipient = $order->mainRecipients[0];

            $statuses = (new StatusManager())->getStatuses();

            $data[] = [
                $order->id,
                $serviceTitles[$order->service_id],
                $firstMainRecipient ? $firstMainRecipient->recipient_name : '',
                $statuses[$order->status],
                $order->liqpay_payment_date ? $order->liqpay_payment_date->format('d-m-Y H:i:s') : '',
                $order->full_name,
                $order->phone,
                $order->email,
                $order->total,
                $order->sum,
                $order->liqpay_real_commission,
                $order->bank_commission,
                $order->profit,
            ];
        }

        //создать лист и задать отступы
        $oSpreadsheet = new Spreadsheet();
        $sheet = $oSpreadsheet->getActiveSheet();
        $sheet->getPageMargins()
            ->setLeft(0.2)
            ->setRight(0.2)
            ->setTop(0.2)
            ->setBottom(0.2);

        $sheet->fromArray($data);

        foreach (range('A', 'M') as $columnID) {
            $sheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }

        //Отдать файл на скачивание в браузер
        $oWriter = IOFactory::createWriter($oSpreadsheet, 'Xlsx');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=\"order-import.xlsx\"");
        header("Cache-Control: max-age=0");
        $oWriter->save('php://output');
    }
}