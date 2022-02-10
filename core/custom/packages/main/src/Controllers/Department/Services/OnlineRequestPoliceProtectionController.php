<?php

namespace EvolutionCMS\Main\Controllers\Department\Services;
use EvolutionCMS\Main\Controllers\BaseController;
use EvolutionCMS\Main\Services\GovPay\Contracts\IField;
use EvolutionCMS\Main\Services\GovPay\Fields\AddressField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\EmailField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\RadioField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;
use EvolutionCMS\Main\Services\GovPay\Fields\FullNameField;
use EvolutionCMS\Main\Services\GovPay\Fields\LayoutFields;
use EvolutionCMS\Main\Services\GovPay\Fields\PhoneField;
use EvolutionCMS\Main\Services\GovPay\Fields\RegionalServiceCenterField;
use EvolutionCMS\Main\Services\GovPay\Fields\ServiceField;
use EvolutionCMS\Main\Services\GovPay\Fields\SubtitleField;
use EvolutionCMS\Main\Services\GovPay\Models\RegionalServiceCenter;
use EvolutionCMS\Main\Services\GovPay\Models\ServiceOnlineRequest;
use EvolutionCMS\Main\Support\Helpers;
use Helpers\FS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mpdf\Mpdf;

class OnlineRequestPoliceProtectionController extends BaseController
{
    public function render()
    {
        $fields = $this->getFormFields();
        foreach ($fields as $field) {
            $renderedFields[] = \View::make($field->getViewFile(), $field->getDataForRender());
        }
        $this->data['form'] = implode('', $renderedFields);
    }

    public function sendForm(Request $request){

        $formData = $request->toArray();

        try {
            $rules = [];
            $values = [];

            $fields = $this->getFormFields();
            foreach ($fields as $field) {
                $values = array_merge($values,$field->getValues($formData));
                $rules = array_merge($rules,$field->getValidationRules());
            }

            Validator::make($values, $rules)->validate();



            $serviceOnlineRequest = ServiceOnlineRequest::create();

            $values['number'] = $serviceOnlineRequest->id;

            $report = \View::make('partials.form.onlineRequestPoliceProtection.report')->with($values)->render();


            $filename = $serviceOnlineRequest->id . date("Y") . '-' . date("m") . '-' . date("d") . '_' . date("H") . '-' . date("i") . '-' . date("s") . '.pdf' ;
            $path = 'assets/files/zajavka/' . date("Y") . '/' . date("m") . '/';



            FS::getInstance()->makeDir($path,'0775');

            $mpdf = new Mpdf([
                'tempDir' => EVO_STORAGE_PATH . 'mdf/'
            ]);
            $mpdf->WriteHTML($report);
            $mpdf->Output(MODX_BASE_PATH . $path.$filename);
            $values['href_doc'] = $path.$filename;


            $serviceOnlineRequest->fill(array_merge($values,[
                'service'=>$values['service_title'],
                'region'=>$values['regional_service_center_title'],
            ]));
            $serviceOnlineRequest->save();

            $mainConfig = [
                'subject'=> 'Заява на послуги ПО',
                'to'=> $this->evo->getConfig('g_sys_form_email').','.$values['email'],
            ];
            if(!$this->evo->sendmail($mainConfig,$report,[
                MODX_BASE_PATH.$path.$filename])){
                throw new \Exception('Не вдалось відправити лист');
            }

            return [
                'status' => true,
                'message' => 'За Вашим зверненням, незабаром зв\'яжуться фахівці технічної служби Поліції охорони. Очікуйте дзвінка. Дякуємо що скористалися нашим сервісом.'
            ];


        } catch (ValidationException $exception) {
            var_dump($exception->errors());
            return [
                'status' => false,
                'errors' => $exception->errors()
            ];
        }
        catch (\Exception $e){
            var_dump($e->getMessage());
            return [
                'status' => false,
                'errors' => [
                    'Сталась помилка'
                ]
            ];
        }

    }


    /**
     * @return IField[]
     */
    private function getFormFields():array
    {

        $formConfig = Helpers::multiFields(json_decode(evo()->documentObject['online_order_police_protection_fc'][1],true))[0];


        return [
            new SubtitleField('Особисті дані'),
            new LayoutFields([
                FullNameField::buildField(
                    $formConfig['full_name_title']??'',
                    $formConfig['full_name_placeholder']??''),
            ]),
            new LayoutFields([
                PhoneField::buildField($formConfig['phone_title']??'',$formConfig['phone_placeholder']??''),
                EmailField::buildField($formConfig['email_title']??'',$formConfig['email_placeholder']??'')
            ]),
            new SubtitleField("Дані об'єкта можнату"),
            new LayoutFields([
                ServiceField::buildField(
                    $formConfig['service_title']??'',
                    $formConfig['service_placeholder']??'',
                    [95],true,false),
                RegionalServiceCenterField::buildField(
                    $formConfig['regional_service_center_title']??'',
                    $formConfig['regional_service_center_placeholder']??'')
            ]),
            new LayoutFields([
                new TextField(
                    'district',
                    $formConfig['district_title']??'',
                    $formConfig['district_placeholder']??'',
                    true,
                    [
                    'not_regex:~[^А-ЯЁ\.\d,ЁЇїЄєІі`\' -]~ui'
                ]),
                AddressField::buildField($formConfig['address_title']??'',$formConfig['address_placeholder']??'')
            ]),
            new LayoutFields([
                new TextField('floor',$formConfig['floor_title']??'',$formConfig['floor_placeholder']??'',true,[
                    'regex:~^(\d{1,2})$~ui' =>'до 2 цифр без пробілів'
                ]),
                new TextField('rooms',$formConfig['rooms_title']??'',$formConfig['rooms_placeholder']??'',true,[
                    'regex:~^(\d{1,2})$~ui' => 'до 2 цифр без пробілів'
                ])
            ]),
            new LayoutFields([
                new RadioField('pet',$formConfig['pet_title']??'',[
                    '1'=>'Так',
                    '0'=>'Ні',
                ],'0'),

                new RadioField('secure',$formConfig['secure_title']??'',[
                    '1'=>'Так',
                    '0'=>'Ні',
                ],'0')

            ])
        ];
    }
}