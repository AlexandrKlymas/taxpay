<?php
namespace EvolutionCMS\Main\Controllers;


use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class FormController
{
    public function callback(Request $request){

        $evo = \EvolutionCMS();

        $data = [];
        /** @var $validator Validator */
        $validator = \Validator::make($request->all(), [
            'name' => ['required',],
            'email' => ['required', 'email'],
            'phone' => ['required'],
        ]);

        if (!$validator->fails()) {
            $data['thanks'] = "Наші менеджери зв'яжуться з вами";

            evolutionCMS()->sendmail([
                'from' => $evo->getConfig('emailsender'),
                'to' => $evo->getConfig('g_sys_form_email'),
                'subject' => 'Сообщение из формы обратной связи',
                'body' => \View::make('partials.form.callback.report', $request)->toHtml()
            ]);

        } else {
            $data['form'] = $request->toArray();
            $data['errors'] = $validator->errors();
        }

        return \View::make('partials.form.callback.form', $data)->toHtml();
    }
}