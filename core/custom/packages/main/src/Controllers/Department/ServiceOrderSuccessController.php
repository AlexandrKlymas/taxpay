<?php

namespace EvolutionCMS\Main\Controllers\Department;

use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ServiceOrderSuccessController extends BaseController
{

    public function render()
    {
        $orderHash = $_GET['order_hash']??'';
        try {
            if (empty($orderHash)) {
                throw new \Exception('empty orderHash');
            }

            $serviceOrder = ServiceOrder::whereOrderHash($orderHash)->first();

            if (empty($serviceOrder)) {
                throw new \Exception('service order not found');
            }

            if (empty($serviceOrder->invoice_file_pdf)) {
                throw new \Exception('empty invoice file: ' . $serviceOrder->invoice_file_pdf);
            }

            $this->data['invoice_pdf'] = $serviceOrder->invoice_file_pdf;
            $this->data['order_hash'] = $orderHash;
            $this->data['liqpay_transaction_id'] = $serviceOrder->liqpay_transaction_id;

            if (!empty($serviceOrder->form_data['backref'])) {
                $this->data['backref'] = $serviceOrder->form_data['backref'];
                $this->data['backref_caption'] = parse_url($serviceOrder->form_data['backref'])['host'] ?? 'сервісу';
            }

        } catch (\Exception $e) {

            $serviceOrderId = 'no ID';

            if (!empty($serviceOrder)) {
                $serviceOrderId = $serviceOrder->id;
            }

            $ref = $_SERVER['HTTP_REFERER']??'no REF';

            evo()->logEvent(1, 3, $e->getMessage().PHP_EOL.$ref,
                'SuccessPage Error order=' . $serviceOrderId . ' ref='.$ref);

            $this->evo->sendRedirect(UrlProcessor::makeUrl(123));
            die();
        }
    }


    static public function sendInvoiceToEmail(Request $request)
    {
        $validator = Validator::make($request->toArray(), [
            'email' => ['required', 'email'],
            'order_hash'=>['required'],
        ]);

        if ($validator->fails()) {
            evo()->logEvent(1,3,json_encode($request->toArray()),'Invoice to Email RQ ERROR');
            return View::make('partials.form.invoiceToEmail.form', $request->toArray())
                ->withErrors($validator->errors())->toHtml();
        }else{
            $orderHash = $request['order_hash'];

            $serviceOrder = ServiceOrder::whereOrderHash($orderHash)->first();

            if(empty($serviceOrder)){
                evo()->logEvent(1,3,json_encode($request->toArray()),'Invoice to Email ORDER ERROR');
                return View::make('partials.form.invoiceToEmail.form', $request->toArray())
                    ->withErrors(['order'=>['Квитанцію не знайдено']])->toHtml();
            }
            if(empty($serviceOrder->invoice_file_pdf)){
                evo()->logEvent(1,3,json_encode($request->toArray()).json_encode($serviceOrder->toArray()),'Invoice to Email INVOICE ERROR');
                return View::make('partials.form.invoiceToEmail.form', $request->toArray())
                    ->withErrors(['order'=>['Квитанцію не знайдено']])->toHtml();
            }
        }

        if(empty($serviceOrder->email)){
            $serviceOrder->email = $request['email'];
            $serviceOrder->updateFormData('email',$request['email']);
            $serviceOrder->save();
        }

        $report = View::make('partials.form.invoiceToEmail.report')->toHtml();

        evo()->sendmail([
            'from' => evo()->getConfig('emailsender'),
            'to' => $request->get('email'),
            'subject' => 'Квитанція про оплату послуги від govpay24.com',
            'body' => $report
        ], '', [$serviceOrder->invoice_file_pdf]);

        evo()->logEvent(1,1,json_encode($request->toArray()).json_encode($serviceOrder->toArray()),'Send Invoice to Email SUCCESS');
        $serviceOrder->historyUpdate('Квитанція відправлена на '. $request['email']);
        $serviceOrder->save();

        return View::make('partials.form.invoiceToEmail.success')->toHtml();
    }
}