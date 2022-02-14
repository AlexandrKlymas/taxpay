<?php

namespace EvolutionCMS\Main\Services\GovPay\Lists\DVS\VVPay;


use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\DateField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\SelectField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\SumField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TotalCaptionField;
use EvolutionCMS\Main\Services\GovPay\Fields\FineSeries;
use EvolutionCMS\Main\Services\GovPay\Fields\FullNameField;
use EvolutionCMS\Main\Services\GovPay\Fields\IpnField;
use EvolutionCMS\Main\Services\GovPay\Fields\LayoutFields;
use EvolutionCMS\Main\Services\GovPay\Fields\SeparatorField;
use EvolutionCMS\Main\Services\GovPay\Fields\TwoRelatedSelectFields;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Models\VVPayDetails;

class VVPayFormConfigurator extends BaseFormConfigurator implements IFormConfigurator
{

    /**
     * @inheritDoc
     */
    public function getFormConfig(): array
    {
        $formConfig = $this->serviceConfig['formConfig'];

        $regions = array_values(VVPayDetails
            ::select(['district', 'region', 'id'])
            ->get()
            ->groupBy('region', 'district')
            ->toArray());

        $relations = [];
        foreach ($regions as $k=>$region) {
            $regionId = $k+1;
            if (!empty($region)) {
                $districts = [];
                $regionTitle = '';
                foreach ($region as $district) {
                    if (empty($regionTitle)) {
                        $regionTitle = $district['region'];
                    }
                    $districts[$district['id']] = [
                        'id' => $district['id'],
                        'title'=> $district['district'],
                        'region_id'=>$regionId,
                        'region_title'=>$district['region'],
                    ];
                }
                $relations[$regionId] = [
                    'id'=>$regionId,
                    'title' => $regionTitle,
                    'districts' => $districts,
                ];
            }

        }
        $regions = [];
        foreach($relations as $relation){
            $regions[$relation['id']] = $relation['title'];
        }

        return [
            new LayoutFields([
                FullNameField::buildField(
                    $formConfig['full_name_title'] ?? '',
                    $formConfig['full_name_placeholder'] ?? ''
                ),
            ]),

            new LayoutFields([
                IpnField::buildField(
                    $formConfig['ipn_title'] ?? '',
                    $formConfig['ipn_placeholder'] ?? ''
                ),
            ]),

            new SeparatorField('Дані виконавчого впровадження'),

            new TwoRelatedSelectFields(
                new LayoutFields(
                    [
                        new SelectField(
                            'region',
                            $formConfig['region_title'] ?? '',
                            $formConfig['region_placeholder'] ?? '',
                            $regions
                        ),
                    ]
                ),
                new LayoutFields(
                    [
                        new SelectField(
                            'district',
                            $formConfig['district_title'] ?? '',
                            $formConfig['district_placeholder'] ?? '',
                            []
                        ),
                    ]
                ),
                $relations
            ),

            new LayoutFields([
                new FineSeries(
                    'series',
                    $formConfig['series_title'] ?? '',
                    $formConfig['series_placeholder'] ?? ''
                ),
                new DateField(
                    'date',
                    $formConfig['date_title'] ?? '',
                    $formConfig['date_placeholder'] ?? ''
                ),
            ]),

            new LayoutFields([
                SumField::build(
                    $formConfig['sum_title'] ?? '',
                    $formConfig['sum_placeholder'] ?? ''
                ),
                TotalCaptionField::build($formConfig['total_title'] ?? ''),
            ])

        ];
    }

    public function renderDataForPreview($fieldValues): array
    {
        return [
            'Прізвище, ім`я та по-батькові' => $fieldValues['full_name'],
            'ІПН'=>$fieldValues['ipn'],
            'Дані виконавчого провадження' => '',
            'Область' => $fieldValues['region_title'],
            'Відділ' => $fieldValues['district_title'],
            'Номер виконавчого провадження' => $fieldValues['series'],
            'Дата виконавчого провадження' => $fieldValues['date'],
        ];
    }
}