<?php

namespace EvolutionCMS\Main\Services\CarInsurance;

use EvolutionCMS\Main\Services\CarInsurance\Models\OsagoMarka;
use EvolutionCMS\Main\Services\CarInsurance\Models\OsagoModel;
use EvolutionCMS\Main\Services\CarInsurance\Models\OsagoOrders;
use EvolutionCMS\Main\Services\CarInsurance\Models\OsagoPaymentInfo;
use EvolutionCMS\Main\Services\LiqPay\LiqPayService;
use EvolutionCMS\Main\Support\Helpers;
use EvolutionCMS\Models\SiteTmplvarContentvalue;
use EvolutionCMS\UrlProcessor;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class CarInsuranceService
{
    protected $session;
    protected $render;
    protected $polisUa;
    protected $shtrafUa;
    protected $validator;
    protected $defaults = [
        'otp' => '1q2wwqqw',
    ];
    protected $statuses = [
      1=>'new',
      2=>'success',
      3=>'error',
    ];
    private bool $testMode = false;

    public function __construct()
    {
        if(evo()->getConfig('dev') === true){
            $this->testMode=true;
        }

        $this->session = new SessionHelper();
        $this->render = new RenderHelper();
        $this->polisUa = new PolisUa($this->testMode);
        $this->shtrafUa = new ShtrafUa();
        $this->validator = new ValidatorService();
    }

    public function getLicensePlateInfo(Request $request): array
    {
        $response = [];

        $this->session->clear();
        $infoCar = $this->loadCarInfoFromApi($request);

        if(!empty($infoCar['errors'])){
            return [
                'car_info' => $this->render->renderLicensePlate(
                    array_merge($this->session->get(), $infoCar))
            ];
        }

        if($this->session->has('cap_class')){
            $this->session->set(['carType'=>$this->session->get('cap_class')]);
        }
        if($this->session->has('year')){
            $this->session->set(['prodYear'=>$this->session->get('year')]);
        }
        if($this->session->has('car_zone')){
            $this->session->set(['carZone'=>$this->session->get('car_zone')]);
        }

        if ($request->has('brand_model')) $this->session->set(['brand_model' => $request->get('brand_model')]);
        if ($request->has('city')) $this->session->set(['city' => $request->get('city')]);
        if ($request->has('vin')) $this->session->set(['vin' => $request->get('vin')]);
        if ($request->has('carType')) $this->session->set(['carType' => $request->get('carType')]);
        if ($request->has('prodYear')) $this->session->set(['prodYear' => (int)$request->get('prodYear')]);
        if ($request->has('franchise')) $this->session->set(['franchise' => $request->get('franchise')]);
        if ($request->has('programId')) $this->session->set(['programId' => $request->get('programId')]);
        if ($request->has('sortby')) $this->session->set(['sortby' => $request->get('sortby')]);
        if ($request->has('sortorder')) $this->session->set(['sortorder' => $request->get('sortorder')]);

//        if ($request->has('brand_model') && $this->session->get('brand_model')) $this->session->set(['brand_model' => $request->get('brand_model')]);
//        if ($request->has('vin') && $this->session->get('vin')) $this->session->set(['vin' => $request->get('vin')]);
//        if ($request->has('carType') && $this->session->get('carType')) $this->session->set(['carType' => $request->get('carType')]);
//        if ($request->has('prodYear') && $this->session->get('prodYear')) $this->session->set(['prodYear' => $request->get('prodYear')]);
//        if ($request->has('franchise') && $this->session->get('franchise')) $this->session->set(['franchise' => $request->get('franchise')]);
//        if ($request->has('programId') && $this->session->get('programId')) $this->session->set(['programId' => $request->get('programId')]);
//        if ($request->has('sortby') && $this->session->get('sortby')) $this->session->set(['sortby' => $request->get('sortby')]);
//        if ($request->has('sortorder') && $this->session->get('sortorder')) $this->session->set(['sortorder' => $request->get('sortorder')]);

        if ($request->has('fullname'))$this->session->set(['user_info'=>['fullname' => $request->get('fullname')]]);
        if ($request->has('birthday'))$this->session->set(['user_info'=>['birthday' => $request->get('birthday')]]);
        if ($request->has('phone'))$this->session->set(['user_info'=>['phone' => $request->get('phone')]]);
        if ($request->has('address'))$this->session->set(['user_info'=>['address' => $request->get('address')]]);
        if ($request->has('ipn'))$this->session->set(['user_info'=>['ipn' => $request->get('ipn')]]);
        if ($request->has('email'))$this->session->set(['user_info'=>['email' => $request->get('email')]]);
        if ($request->has('driving_licence'))$this->session->set(['user_info'=>['driving_licence' => $request->get('driving_licence')]]);
        if ($request->has('driving_licence_date'))$this->session->set(['user_info'=>['driving_licence_date' => $request->get('driving_licence_date')]]);
        if ($request->has('issued_by'))$this->session->set(['user_info'=>['issued_by' => $request->get('issued_by')]]);
        if ($request->has('insurance_date'))$this->session->set(['user_info'=>['insurance_date' => $request->get('insurance_date')]]);
        if ($request->has('sum'))$this->session->set(['user_info'=>['sum' => $request->get('sum')]]);

        $cars = $this->getCars();

        foreach($this->session->get('carTypes') as $car){
            if($car['id']==$this->session->get('carType')){
                $this->session->set(['carTypeName'=>$car['name']]);
            }
        }
        if(!empty($this->session->get('city'))
            &&empty($this->session->get('cityId'))
            ||empty($this->session->get('cityZone'))){
            $cityList = $this->getCity($request->get('city')??$this->session->get('city'));
            if (count($cityList) == 1 && $cityList[0]['city'] === $this->session->get('city')) {
                $this->session->set(['city' => $cityList[0]['city']]);
                $this->session->set(['cityId' => $cityList[0]['regId']]);
                $this->session->set(['cityZone' => $cityList[0]['zone']]);
            }
        }

        if ($request->get('vin') !== null) {
            $validator = $this->validator->vehicleInfo($this->session->get());
            if ($validator->fails()) {

                return [
                    'car_info' => $this->render->renderCarInfoForm(
                        array_merge($this->session->get(), [
                            'cars' => $cars,
                            'errors' => $validator->errors()->toArray()]))
                ];
            }
        }
        $valid = $this->validator->programsRequest($this->session->get());

        if (empty($valid['error'])) {
            $franchise = (int)empty($this->session->get('franchise'))?0:$this->session->get('franchise');
            $calc = [
                'agentId' => $this->polisUa->agentId,
                'carType' => $this->session->get('carType'),
                'carRegZone' => $this->session->get('cityZone'),
                'cityId' => $this->session->get('cityId'),
                'franchiseFrom' => $franchise,
                'franchiseTo' => $franchise > 2499 ? 3000 : $franchise,
                'privilegeType' => 0,
                'taxi' => false,
                'clientType' => 'FL',
                'fraud' => false,
                'vehicleRegistration' => $this->session->get('license_plate'),
            ];
            $this->session->set(['calc' => $calc]);
            $this->session->set([
                'programs' => $this->preparePrograms(
                    $this->polisUa->getPrograms($calc), [
                    'sortBy' => $request->get('sortBy'),
                    'sortOrder' => $request->get('sortOrder')])]);
            if (!empty($request->has('programId'))) {
                foreach ($this->session->get('programs') as $program) {
                    if ($program['id'] == $request->get('programId')) {
                        $this->session->set(['program' => $program]);
                        break;
                    }
                }
            }
        }

        $programs = $this->session->get('programs');
        if (empty($programs)) $programs = [];

        $response['info'] = $this->render->renderPrograms([
            'programs' => $programs,
            'program' => $this->session->get('program'),
            'franchise' => $this->session->get('franchise'),
            'sort' => [
                'sortBy' => $request->get('sortBy'),
                'sortOrder' => $request->get('sortOrder')
            ],
        ]);

        $response['car_info'] = $this->render->renderCarInfoForm(
            array_merge($this->session->get(), ['cars' => $cars]));

        $arrRequest = $request->toArray();
        if(empty($arrRequest['insurance_date'])){
            $arrRequest['insurance_date'] = date("d.m.Y", strtotime('+1 day'));
        }
        if(empty($arrRequest['sum'])){
            $arrRequest['sum'] = $this->session->get('program')['costs'] ?? '';
        }

        $response['insurance_info'] = $this->render->renderInsuranceForm([
            'form' => $arrRequest,
        ]);

        return $response;
    }

    protected function getCars(): array
    {
        $results = [];

        foreach (OsagoMarka::all()->toArray() as $marka) {
            foreach (OsagoModel::where('markId', $marka['markId'])->get()->toArray() as $model) {
                $results[] =
                    mb_convert_case(
                        $marka['markName'], MB_CASE_UPPER, "UTF-8") . ' '
                    . mb_convert_case(
                        $model['modelName'], MB_CASE_UPPER, "UTF-8");
            }
        }
        return $results;
    }

    protected function loadCarInfoFromApi(Request $request)
    {
        $this->session->set(['license_plate' => $request->get('license_plate')]);

        $info = $this->polisUa->getLicensePlateInfo(
            $this->session->get('license_plate'));

        if(!empty($info['data']['car'])){
            $this->session->merge($info['data']['car']);
        }else{
            return ['errors'=>
                ['license_plate'=>
                    ['Ми не знайшли авто з таким номером. Перевірте, чи немає помилок і введіть номер знову']]];
        }

        $fines = $this->shtrafUa->findFine(
            $this->session->get('license_plate'));
        if (!empty($fines['data']['car'])) {
            $car = $fines['data']['car'];
            $this->session->merge([
                'brand_model' => $car['brand_model'],
                'prodYear' => $car['prodYear'],
                'brand' => $car['advanced_info']['brand'],
                'model' => $car['advanced_info']['model'],
                'city' => $car['city'],
            ]);
        }

        if (empty($this->session->get('brand_model'))) {
            $this->session->merge(['brand_model' => $this->makeBrandModel()]);
        }

        $this->session->set(['carTypes' => $this->polisUa->getCarTypes()]);
        $this->session->set(['cityZones' => $this->getCityZones()]);
        $this->setDefaultInfo();

        return ['success'=>true];
    }

    public function setDefaultInfo()
    {
        foreach ($this->defaults as $k => $default) {
            if (empty($this->session->get($k))) {
                $this->session->set([$k => $default]);
            }
        }
    }

    public function getCity(string $city=''): array
    {
        $limit = 5;
        $result = [];
        if(empty($city)){
            return $result;
        }
        foreach ($this->session->get('cityZones') as $cityZone) {
            if (strpos($cityZone['city'], $city) !== false) {
                $result[] = $cityZone;
            }
            if (count($result) >= $limit) {
                break;
            }
        }
        return $result;
    }

    public function getCityZones(): array
    {
        $result = [];
        foreach ($this->polisUa->getCarRegZones() as $zone) {
            foreach ($zone['cities'] as $city) {
                $result[] = [
                    'city' => $city['city'],
                    'regId' => $city['mtsbuCode'],
                    'zone' => $zone['id'],
                ];
            }
        }
        return $result;
    }

    protected function makeBrandModel(): string
    {
        $carInfo = $this->session->get();
        if (!empty($carInfo['brand'])
            && !empty($carInfo['model'])) {
            return $carInfo['brand'] . ' ' . $carInfo['model'];
        }
        if (!empty($carInfo['markName'])
            && !empty($carInfo['modelName'])) {
            return $carInfo['markName'] . ' ' . $carInfo['modelName'];
        }
        return '';
    }

    protected function getMerchantDetails(string $companyId = null)
    {
        $merchants = Helpers::multiFields(
            json_decode(
                SiteTmplvarContentvalue::where('contentid', 156)
                    ->where('tmplvarid', 31)
                    ->first()['value'], true));
        if (!empty($companyId)) {
            foreach ($merchants as $merchant) {
                if ($merchant['id'] == $companyId) {
                    return $merchant;
                }
            }
            return [];
        }

        return $merchants;
    }

    public function getContract(Request $request): array
    {
        $program = $this->session->get('program');

        $contract = [];
        if (!empty($this->session->get('program'))) {
            $program = $this->session->get('program');

            if(empty($this->session->get('markName')) || empty($this->session->get('modelName'))){
                $this->session->set(['markName'=>explode(' ', $this->session->get('brand_model'))[0]]);
                $this->session->set(['modelName'=>explode(' ', $this->session->get('brand_model'))[1]]);
            }

            $req = [
                'calculator' => $this->session->get('calc'),
                'paySum' => $program['costs'],
                'programId' => $program['id'],
                'dateFrom' => date("Y-m-d", strtotime($request->get('insurance_date'))),
                'agentId' => $this->polisUa->agentId,
                'vehicleBrandName' => $this->session->get('markName'),
                'vehicleModelName' => $this->session->get('modelName'),
                'vehicleModelYear' => $this->session->get('prodYear'),
                'vehicleVin' => $this->session->get('vin'),
                'vehicleRegistration' => $this->session->get('license_plate'),
                'insurantPhone' => preg_replace("/[^0-9]/", '', $request->get('phone')),
                'insurantInnEgrpou' => $request->get('ipn'),
                'insurantSurnameOrgName' => explode(' ', $request->get('fullname'))[0] ?? null,
                'insurantName' => explode(' ', $request->get('fullname'))[1] ?? null,
                'insurantPatronymic' => explode(' ', $request->get('fullname'))[2] ?? null,
                'insurantBirthDate' => empty($request->get('birthday')) ? null : date("Y-m-d",
                    strtotime($request->get('birthday'))),
                'insurantAddress' => $request->get('address'),
                'insurantEmail' => $request->get('email'),
                'insurantDocumentType' => '571e45112fc6841bed2da4c7',
                'insurantDocumentSeries' => explode(' ', $request->get('driving_licence'))[0] ?? null,
                'insurantDocumentNumber' => explode(' ', $request->get('driving_licence'))[1] ?? null,
                'insurantDocumentIssueDate' => empty($request->get('driving_licence_date')) ? null : date("Y-m-d",
                    strtotime($request->get('driving_licence_date'))),
                'insurantDocumentIssued' => $request->get('issued_by'),
                'otp' => $this->session->get('otp'),
                'dgoTarif' => $program['dgoConfig']['dgoParams'][0]['dgoTarif']??null,
                'dgoInsurSum' => $program['dgoConfig']['dgoParams'][0]['coverage']??null,
                'dgoPaySum' => $program['dgoConfig']['dgoParams'][0]['cost']??null,
                'polisType' => 'DIGITAL',
                'payType' => 'fullpay',
            ];
            $validator = $this->validator->getContract($req);
            $response = [];
            if (is_array($validator)) {
                $errors = $validator;
            } else {
                if ($validator->fails()) {

                    $errors = $this->replaceCarInfoKeys(
                        $validator->errors()->toArray());
                }
            }
            if (!empty($errors)) {
                $response['car_info'] = $this->render->renderCarInfoForm(
                    array_merge($this->session->get(), [
                        'cars' => $this->getCars(),
                        'errors' => $errors]));

                $response['insurance_info'] = $this->render->renderInsuranceForm([
                    'form' => array_merge($request->toArray(), [
                        'errors' => $errors,
                    ])
                ]);
                return $response;
            }

            $contract = $this->polisUa->getContract($req);
            $this->session->set(['insurance_request'=>$req]);
            if ($contract['result']=='success' && !empty($contract['contractId'])) {
                $this->session->set(['contract' => $contract]);
                $payment = $this->polisUa->getPayment($contract['contractId']);
                $this->session->set(['payment' => $payment]);
            }else{
                if(!empty($contract['errorMessage']) && strpos($contract['errorMessage'],'error')===false){
                    return [
                        'status'=>'error',
                        'error' => $contract['errorMessage'],
                    ];
                }
            }
        }

        return [
            'payment' => $this->render->renderPayForm([]),
            'info' => $this->render->renderContract([
                'contract' => $contract,
                'payment' => $payment??[],
                'program' => $program,
                'user' => $this->session->get(),
            ]),
            'payment_form' => $this->pay(),
        ];
    }

    protected function preparePrograms(array $programs, array $sort): array
    {
        if(empty($programs)){
            return [];
        }
        if ($sort['sortBy'] == 'fullprice') {
            if ($sort['sortOrder'] == 'asc') {
                usort($programs, function ($a, $b) use ($programs) {
                    return ($b['fullPrice'] - $a['fullPrice']);
                });
            } else {
                usort($programs, function ($a, $b) {
                    return ($a['fullPrice'] - $b['fullPrice']);
                });
            }
        } elseif ($sort['sortBy'] == 'raiting') {
            if ($sort['sortOrder'] == 'asc') {
                usort($programs, function ($a, $b) {
                    return ($b['companyRate'] - $a['companyRate']);
                });
            } else {
                usort($programs, function ($a, $b) {
                    return ($a['companyRate'] - $b['companyRate']);
                });
            }
        }
        $merchants = $this->getMerchantDetails();
        foreach ($programs as $k => $program) {
            foreach ($merchants as $merchant) {
                if ($program['companyId'] == $merchant['id']) {
                    $programs[$k]['merchant'] = $merchant;
                }
            }
        }

        return $programs;
    }

    public function replaceCarInfoKeys(array $array): array
    {
        $aliases = [
            'dateFrom' => 'insurance_date',
            'insurantInnEgrpou' => 'ipn',
            'insurantSurnameOrgName' => 'fullname',
            'insurantName' => 'fullname',
            'insurantPatronymic' => 'fullname',
            'insurantBirthDate' => 'birthday',
            'insurantPhone' => 'phone',
            'insurantAddress' => 'address',
            'insurantEmail' => 'email',
            'insurantDocumentSeries' => 'driving_licence',
            'insurantDocumentNumber' => 'driving_licence',
            'insurantDocumentIssueDate' => 'driving_licence_date',
            'insurantDocumentIssued' => 'issued_by',
            'paySum' => 'sum',
        ];
        $newArray = [];
        foreach ($array as $k => $arr) {
            if (!empty($aliases[$k])) {
                $newArray[$aliases[$k]] = $arr;
            }
        }

        return $newArray;
    }

    public function pay(): string
    {
        $defaultError = 'Вибачте, сервіс тимчасово недоступний. Спробуйте, будь ласка, пізніше.';

        $program = $this->session->get('program');
        if (empty($program)) {
            return $this->render->renderPayError(['error'=>$defaultError]);
//            evo()->sendRedirect(\UrlProcessor::makeUrl(124));
        }

        $payment = $this->session->get('payment');
        if (empty($payment)) {
            return $this->render->renderPayError(['error'=>$defaultError]);
//            evo()->sendRedirect(\UrlProcessor::makeUrl(124));
        }

        $merchant = $program['merchant'] ?? [];
        if (empty($merchant)) {
            return $this->render->renderPayError(['error'=>$defaultError]);
//            evo()->sendRedirect(\UrlProcessor::makeUrl(124));
        }

        if($this->testMode){
            $publicKey = evo()->getConfig('g_sys_public_key_sandbox');
            $privateKey = evo()->getConfig('g_sys_private_key_sandbox');
        }else{
            $publicKey = $merchant['public'];
            $privateKey = $merchant['secret'];
        }

        $liqPay = new \LiqPay($publicKey, $privateKey);
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . '://'
            . $_SERVER['HTTP_HOST'];

        $paymentParams = [
            'action' => 'pay',
            'expired_date' => date('Y-m-d') . ' 23:50:00',
            'amount' => $payment['paySum'],
            'currency' => 'UAH',
            'description' => $payment['purpose'],
            'order_id' => $payment['contractId'],
            'version' => '3',
            'language' => 'uk',
            'result_url' => $baseUrl . '/pay-osago-check',
            'server_url' => $baseUrl . '/pay-osago-callback',
        ];

        $order = OsagoOrders::updateOrCreate([
            'contract_id' => $payment['contractId'],
        ],[
            'insurance_params' => json_encode($this->session->get()),
            'status' => 1,
            'payment_params' => json_encode($paymentParams),
        ]);
        $insuranceRequest = $this->session->get('insurance_request');
        $fullName = $insuranceRequest['insurantSurnameOrgName']
            . ' ' . $insuranceRequest['insurantName']
            . ' ' . $insuranceRequest['insurantPatronymic']
        ;
        OsagoPaymentInfo::updateOrCreate([
            'payment_order'=> $order->id,
        ],[
            'form_id'=>'111',
            'doc_id'=>'111',
            'fio'=>$fullName,
            'address'=>$insuranceRequest['insurantAddress'],
            'phone'=>$insuranceRequest['insurantPhone'],
            'email'=>$insuranceRequest['insurantEmail'],
            'post_info'=> json_encode($payment),
            'poluch_name'=>$program['companyName'],
            'summ_original'=>$program['costs'],
            'itogo'=>$program['costs'],
            'date'=>date('Y-m-d h:i:s',strtotime('now')),
            'status_date'=>date('Y-m-d h:i:s',strtotime('now')),
            'status'=>1,
            'create_date'=>date('Y-m-d h:i:s',strtotime('now')),
        ]);

        return $liqPay->cnb_form($paymentParams);
    }

    public function payCheck(Request $request)
    {
        evo()->logEvent(1,2,json_encode($request->toArray()),'check Payment');

        if(!empty($request['data'])){
            $data = $this->payCallback($request);
        }
        if (!empty($data)) {
            $order = OsagoOrders::where('contract_id', $data['order_id'])
                ->first();
            if (!empty($order)) {
                if(!empty($order->payment_callback)){
                    $callback = json_decode($order->payment_callback,true);
                    if(empty($order->contract_payment)){
                        $payment = [
                            'dateTime'=>date('Y-m-d\TH:i:s', $callback['create_date']*0.001),
                            'reference'=>(string)$callback['transaction_id'],
                            'purpose'=>$callback['description'],
                            'sum'=>$callback['amount'],
                        ];
//                        dd($order);
                        $bindResult = $this->bindPayment($order->contract_id,$payment);

                        OsagoOrders::where('id',$order->id)
                            ->update(['contract_payment'=>json_encode($bindResult)]);
                        $order = OsagoOrders::where('id', $order->id)
                            ->first();

                        if($bindResult['result']=='success'){
                            OsagoOrders::where('id',$order->id)
                                ->update(['status'=>2]);
                            OsagoPaymentInfo::where('payment_order',$order->id)
                                ->update([
                                    'status'=>2,
                                    'href_order_pdf'=>$this->printContract($order->contract_id),
                                    'date'=>date('Y-m-d h:i:s',strtotime('now')),
                                    'status_date'=>date('Y-m-d h:i:s',strtotime('now')),
                                ]);
                            $this->session->set(['last_contract'=>$order->contract_id]);
                            evo()->sendRedirect(\UrlProcessor::makeUrl(157));
                        }else{
                            OsagoOrders::where('id',$order->id)
                                ->update(['status'=>3]);
                            OsagoPaymentInfo::where('payment_order',$order->id)
                                ->update([
                                    'status'=>3,
                                    'date'=>date('Y-m-d h:i:s',strtotime('now')),
                                    'status_date'=>date('Y-m-d h:i:s',strtotime('now')),
                                ]);
//                            dd($order->toArray(),'bind fail');
                            evo()->sendRedirect(\UrlProcessor::makeUrl(124));
                        }
                    }else{
                        if($order->status == 2){
                            $this->session->set(['last_contract'=>$order->contract_id]);
                            evo()->sendRedirect(\UrlProcessor::makeUrl(157));
                        }else{
//                            dd($order->toArray(),'status');
                            evo()->sendRedirect(\UrlProcessor::makeUrl(124));
                        }
                    }
                }
//                dd($order->toArray(),'payment callback');
                evo()->sendRedirect(\UrlProcessor::makeUrl(124));
            }
//            dd($order->toArray(),'order');
            evo()->sendRedirect(\UrlProcessor::makeUrl(124));
        }
//        dd('no contract_id');
        evo()->sendRedirect(\UrlProcessor::makeUrl(124));
    }

    public function checkSignature(Request $request): bool
    {
        $data = json_decode(base64_decode($request['data']), true);

        evo()->logEvent(1,2,json_encode($data),'Автоцивілка csign DATA');

        $order = OsagoOrders::where('contract_id',$data['order_id'])
            ->first();
        if(empty($order)){
            evo()->logEvent(1,2,json_encode([$request->toArray(),$data]),'Автоцивілка csign заказ не знайдено');
            return false;
        }

        $companyId = json_decode($order->insurance_params,true)['contract']['companyId'];

        if(empty($companyId)){
            evo()->logEvent(1,2,json_encode([$request->toArray(),$order->toArray(),$data]),'Автоцивілка csign відсутні данні про компанію');
            return false;
        }

        $merchant = $this->getMerchantDetails($companyId);

        if(empty($merchant)){
            evo()->logEvent(1,2,json_encode([$request->toArray(),$data]),'Автоцивілка csign мерчант не знайдено');
            return false;
        }

        if($this->testMode){
            $privateKey = evo()->getConfig('g_sys_private_key_sandbox');
        }else{
            $privateKey = $merchant['secret'];
        }

        $sign = base64_encode( sha1($privateKey . $request['data'] . $privateKey, 1 ));

        $validSign = $sign == $request['signature'];

        if(!$validSign){
            evo()->logEvent(1,2,json_encode($request->toArray()),'Автоцивілка csign помилка сігнатури');
        }

        return $validSign;
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function payCallback(Request $request)
    {
        if(!empty($request['data'])){
            $data = json_decode(base64_decode($request['data']), true);
        }else{
            $data = [];
        }

        if(!$this->checkSignature($request)){
            $data = [];
        }

        evo()->logEvent(1,2,json_encode(['request'=>$request->toArray(),'data'=>$data]),'payCallback');

        if (!empty($data)) {
            $order = OsagoOrders::where('contract_id', $data['order_id'])
                ->first();
            if (!empty($order)) {
                $order = OsagoOrders::where('id', $order->id)
                    ->update(['payment_callback' => json_encode($data)]);

            }
        }

        if($data['status']!=='success'){
            evo()->logEvent(1,2,json_encode([
                'request'=>$request->toArray(),
                'data'=>$data]),'Автоцивілка помилка оплати');
            $data = [];
        }
        return $data;
    }
    public function bindPayment(string $contractId, array $payment): array
    {
        return $this->polisUa->bindPayment($contractId,$payment);
    }
    public function printContract(string $contractId):string
    {
        return $this->polisUa->printContract($contractId);
    }
    public function getLastContract():string
    {
        return $this->session->get('last_contract');
    }
}