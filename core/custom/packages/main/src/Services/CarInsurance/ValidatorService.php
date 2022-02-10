<?php

namespace EvolutionCMS\Main\Services\CarInsurance;


class ValidatorService
{
    public function carInfo(array $array): array
    {
        if(empty($array['vin'])){
            return ['error'=>'Ми не знайшли авто з таким номером.'
                .' Перевірте, чи немає помилок і введіть номер знову'];
        }
        if(!empty($array['error'])){
            return ['error'=>'Виникла помилка. Спробуйте пізніше.'];
        }
        return ['success'];
    }

    public function programsRequest(array $array): array
    {
//        bdump($array);
        if(empty($array['carType'])
        || empty($array['cityZone'])
        || empty($array['cityId'])
        ){
            return ['error'=>'Стрвхові компанії не знайдено.'];
        }
        return ['success'];
    }

    public function vehicleInfo(array $array)
    {
        return \Validator::make($array,[
            'brand_model' => ['required'],
            'prodYear' => [
                'required',
//                'size:4',
//                'min:1900',
                'numeric',
                'min:1900',
                'max:'.date('Y',time()),
            ],
            'vin' => ['required'],
            'license_plate' => ['required'],
        ],[
            'brand_model.required' => 'Марка і модель ТЗ обов`язкова до заповнення',
            'prodYear.required' => 'Рік ТЗ обов`язковий до заповнення',
            'prodYear.numeric' => 'Рік ТЗ повинен мати формат (2010)',
            'prodYear.min' => 'Рік ТЗ повинен бути не більший за 1900',
            'prodYear.max' => 'Рік ТЗ повинен бути не більший за поточний '.date('Y',time()),
            'vin.required' => 'VIN код обов`язковий до заповнення',
            'license_plate.required' => 'Номер ТЗ обов`язковий до заповнення',
        ]);
    }

    public function getContract(array $array)
    {
        $validator = \Validator::make($array,[
            'calculator' => ['required'],
            'paySum' => ['required'],
            'programId' => ['required'],
            'agentId' => ['required'],
            'insurantDocumentType' => ['required'],
            'otp' => ['required'],
//            'dgoTarif' => ['required'],
//            'dgoInsurSum' => ['required'],
//            'dgoPaySum' => ['required'],
        ], [
            'calculator.required'=>'Помилка підбору програми страхування',
            'paySum.required'=>'Помилка сумми програми страхування',
            'programId.required'=>'Помилка підключення програми страхування',
            'agentId.required' => 'Помилка ідентифікатора агента страхування',
            'insurantDocumentType.required' => 'Помилка типу документа для ідентифікації',
            'otp.required' => 'Помилка ОТП',
//            'dgoTarif.required' => 'Помилка тарифу обраної програми страхування',
//            'dgoInsurSum.required' => 'Помилка в розрахунку сумми страхування',
//            'dgoPaySum.required' => 'Помилка в суммі оплати страхування',
        ]);

        if($validator->fails()){
            evo()->logEvent(1,2,json_encode($validator->errors()->toArray()),'Автоцивілка. Ошибка данны оформления кортракта');
            return ['service'=>'Помилка сервісу. Спробуйте будь ласка пізніше'];
        }
//        bdump($array);
        return \Validator::make($array, [
            'insurantPhone' => ['required','digits:12'],
            'insurantInnEgrpou' => ['required'],
            'insurantSurnameOrgName' => ['required'],
            'insurantName' => ['required'],
            'insurantPatronymic' => ['required'],
            'insurantBirthDate' => ['required'],
            'insurantAddress' => ['required'],
            'insurantEmail' => ['required'],
            'insurantDocumentSeries' => ['required'],
            'insurantDocumentNumber' => ['required'],
            'insurantDocumentIssueDate' => ['required'],
            'insurantDocumentIssued' => ['required'],
            'dateFrom' => ['required','date','after:'.date('Y-m-d',strtotime(date('Y-m-d')))],
        ], [
            'insurantPhone.required' => 'Телефон обов`язковий до заповнення',
            'insurantPhone.digits_between' => 'Телефон повинен мати формат (380987654321)',
            'insurantInnEgrpou.required' => 'ІПН обов`язковий до заповнення',
            'insurantSurnameOrgName.required' => 'Прізвище обов`язкове до заповнення',
            'insurantName.required' => 'Ім`я обов`язкове до заповнення',
            'insurantPatronymic.required' => 'По-батькові обов`язкове до заповнення',
            'insurantBirthDate.required' => 'Дата народження обов`язкова до заповнення',
            'dateFrom.required' => 'Дата початку дії полісу обов`язкова до заповнення',
            'dateFrom.date' => 'Дата початку дії полісу повинна мати формат (01.01.2021)',
            'dateFrom.after' => 'Дата початку дії полісу може бути не раніше '
                .date('d.m.Y',strtotime('+1 day')),
            'insurantAddress.required' => 'Адреса обов`язкова до заповнення',
            'insurantEmail.required' => 'Email обов`язковий до заповнення',
            'insurantDocumentSeries.required' => 'Серія посвідчення водія обов`язкова до заповнення',
            'insurantDocumentNumber.required' => 'Номер посвідчення водія обов`язковий до заповнення',
            'insurantDocumentIssueDate.required' => 'Дата видачі обов`язкова до заповнення',
            'insurantDocumentIssued.required' => 'Ким видано обов`язково до заповнення',
        ]);
    }
}