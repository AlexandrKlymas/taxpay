<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\BaseService;


use EvolutionCMS\Main\Services\GovPay\Calculators\FinalPaymentCalculator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IPreviewGenerator;
use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IServiceFactory;
use Illuminate\Support\Facades\View;

class BasePreviewGenerator implements IPreviewGenerator
{
    protected IServiceFactory $serviceFactory;

    public function __construct(IServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
    }
    public function getPreview(array $data):string
    {
        return View::make('partials.services.preview')->with($data)->render();
    }

    public function getDataForPreview(array $requestData): array
    {
        $finalPaymentCalculator = new FinalPaymentCalculator($this->serviceFactory->getServiceId());

        $paymentRecipientGenerator = $this->serviceFactory->getPaymentRecipientsGenerator();

        $formConfigurator = $this->serviceFactory->getFormConfigurator();

        $formFieldsValues = $formConfigurator->getFormFieldsValues($requestData);

        $recipients = $paymentRecipientGenerator->getPaymentRecipients($formFieldsValues);

        $previewFormData = $this->serviceFactory->getFormConfigurator()->renderDataForPreview($formFieldsValues);

        $commissions = $this->serviceFactory->getCommissionsManager()->getCommissions();

        return [
            'commissions'=>$commissions,
            'requestData'=>$requestData,
            'recipient' => end($recipients),
            'formData' => $previewFormData,
            'amount' => $finalPaymentCalculator->calculate($requestData)
        ];
    }

    public function generatePreview(array $requestData): string
    {
        return $this->getPreview(
            $this->getDataForPreview($requestData));
    }
}