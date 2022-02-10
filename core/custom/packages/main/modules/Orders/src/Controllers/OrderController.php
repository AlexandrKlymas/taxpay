<?php
namespace EvolutionCMS\Main\Modules\Orders\Controllers;


use EvolutionCMS\Main\Services\GovPay\Managers\ServiceManager;
use EvolutionCMS\Main\Services\GovPay\Managers\StatusManager;
use EvolutionCMS\Main\Services\GovPay\Models\PaymentRecipient;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use EvolutionCMS\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use View;
use function Symfony\Component\String\b;

class OrderController extends BaseController
{


    public function __construct()
    {
        parent::__construct();

    }
    public function loadDataForOrderForm(Request $request)
    {
        $orderId = $request->get('orderId');
        $order = ServiceOrder::findOrFail($orderId);

        return [
            'id' => $order->id,
            'service_id' => $order->service_id,
            'status' => $order->status,
            'full_name' => $order->form_data['full_name'],
            'total' => $order->total,
            'sum' => $order->sum,
            'liqpay_real_commission' => $order->liqpay_real_commission,
            'bank_commission' => $order->bank_commission,
            'profit' => $order->profit,

            'liqpay_payment_date' => $order->liqpay_payment_date? $order->liqpay_payment_date->format('Y-m-d H:i:s'):''
        ];
    }

    public function loadRecipients(Request $request){
        $orderId = $request->get('orderId');
        $order = ServiceOrder::findOrFail($orderId);


        return $order->recipients->toArray();
    }


    public function makePdf(Request $request){
        $serviceOrder = ServiceOrder::find($request->get('orderId'));

        $serviceManager = new ServiceManager();
        $serviceManager->generateInvoice($serviceOrder->id,true);

        return [
            'status'=>'success'
        ];
    }

    public function delete(Request $request){
        ServiceOrder::findOrFail($request->toArray()['id'])->delete();

        return [
            'status'=>'success'
        ];

    }
    public function save(Request $request){


        try {
            $this->trySave($request);
            return [
                'status'=>'success'
            ];
        }
        catch (\Exception $e){
            return [
                'error'=>$e->getMessage(),
                'status'=>'invalid'
            ];
        }

    }

    private function trySave(Request $request){
        $serviceOrderData = json_decode($request->get('serviceOrder'),true);
        $recipientsData = json_decode($request->get('recipients'),true);


        $serviceOrder = ServiceOrder::findOrFail($serviceOrderData['id']);


        if($serviceOrder->status != $serviceOrderData['status']){
            (new StatusManager())->forceChange($serviceOrderData['status'],$serviceOrder);
        }



        if($serviceOrderData['liqpay_payment_date']){
            $serviceOrder->liqpay_payment_date = \Carbon\Carbon::createFromTimeString($serviceOrderData['liqpay_payment_date']);
        }

        $serviceOrder->updateFormData('full_name',$serviceOrderData['full_name']);

        $serviceOrder->total = round(floatval($serviceOrderData['total']),2);
        $serviceOrder->sum = round(floatval($serviceOrderData['sum']),2);
        $serviceOrder->liqpay_real_commission = round(floatval($serviceOrderData['liqpay_real_commission']),2);
        $serviceOrder->bank_commission = round(floatval($serviceOrderData['bank_commission']),2);
        $serviceOrder->profit = round(floatval($serviceOrderData['profit']),2);
        $serviceOrder->historyUpdate('Зміни з адмін панелі');
        $serviceOrder->save();




        foreach ($recipientsData as $recipientFromForm) {
            $recipient = PaymentRecipient::findOrFail($recipientFromForm['id']);

            $recipient->mfo = $recipientFromForm['mfo'];
            $recipient->account = $recipientFromForm['account'];
            $recipient->edrpou = $recipientFromForm['edrpou'];
            $recipient->purpose = $recipientFromForm['purpose'];
            $recipient->recipient_name = $recipientFromForm['recipient_name'];
            $recipient->recipient_bank_name = $recipientFromForm['recipient_bank_name'];
            $recipient->amount = round(floatval($recipientFromForm['amount']),2);

            $recipient->save();
        }
    }

    public function getLiqpayResponse(Request $request){


        $serviceOrder = ServiceOrder::findOrFail($request->get('orderId'));

        return [
            'status'=>'success',
            'liqpayResponse'=>$serviceOrder->liqpay_response
        ];
    }
    public function getHistory(Request $request){


        $serviceOrder = ServiceOrder::findOrFail($request->get('orderId'));

        return [
            'status'=>'success',
            'history'=>$serviceOrder->history
        ];
    }

}