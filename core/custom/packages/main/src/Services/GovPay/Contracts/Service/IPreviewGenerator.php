<?php

namespace EvolutionCMS\Main\Services\GovPay\Contracts\Service;


interface IPreviewGenerator
{
    public function __construct(IServiceFactory $serviceFactory);
    public function getPreview(array $data):string;
    public function getDataForPreview(array $requestData): array;
}