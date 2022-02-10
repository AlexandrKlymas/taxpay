<?php

namespace EvolutionCMS\Main\Controllers\Info;

use EvolutionCMS\Main\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class ContactsController extends BaseController
{
    public function noCacheRender()
    {
        $request = $this->evo->make(Request::class);

        if ($request->has('formid') && $request->get('formid') == 'contacts') {
            $data = $this->processedRequest($request);
            $this->data = array_merge($this->data,$data);
        }
    }

    public function ajaxForm(Request $request)
    {
        $data =  $this->processedRequest($request);

        return \View::make('partials.form.callback.form',$data);
    }

    private function processedRequest(Request $request)
    {
        $data = [];
        /** @var $validator Validator */
        $validator = \Validator::make($request->all(), [
            'name' => ['required',],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'message' => ['required'],
        ],['message.required'=>"Поле Повідомлення є обов'язковим для заповнення."]);

        if (!$validator->fails()) {
            $data['thanks'] = "Наші менеджери зв'яжуться з вами";

            $report = \View::make('partials.form.contacts.report', $request)->toHtml();

            evolutionCMS()->sendmail([
                'from' => $this->evo->getConfig('emailsender'),
                'to' => $this->evo->getConfig('g_sys_form_email'),
                'subject' => 'Сообщение из формы обратной связи',
                'body' => $report
            ]);

        } else {
            $data['form'] = $request->toArray();
            $data['errors'] = $validator->errors();
        }
        return $data;
    }
}