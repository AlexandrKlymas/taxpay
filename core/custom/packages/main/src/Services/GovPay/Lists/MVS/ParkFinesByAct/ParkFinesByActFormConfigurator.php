<?php
namespace EvolutionCMS\Main\Services\GovPay\Lists\MVS\ParkFinesByAct;


use EvolutionCMS\Main\Services\GovPay\Contracts\Service\IFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\SelectField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\SumField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TextField;
use EvolutionCMS\Main\Services\GovPay\Fields\Base\TotalCaptionField;
use EvolutionCMS\Main\Services\GovPay\Fields\FineSeries;
use EvolutionCMS\Main\Services\GovPay\Fields\FullNameField;
use EvolutionCMS\Main\Services\GovPay\Fields\LayoutFields;
use EvolutionCMS\Main\Services\GovPay\Fields\SeparatorField;
use EvolutionCMS\Main\Services\GovPay\Lists\BaseService\BaseFormConfigurator;
use EvolutionCMS\Main\Services\GovPay\Models\ParkPencodeItem;
use EvolutionCMS\Main\Services\GovPay\Models\PencodesItem;

class ParkFinesByActFormConfigurator extends BaseFormConfigurator implements IFormConfigurator
{

    public function getFormConfig(): array
    {
        $formConfig = $this->serviceConfig['formConfig'];

        $regions = ParkPencodeItem
            ::select()
            ->orderBy('id')
            ->get()
            ->pluck('name_ua','id')
            ->toArray()
        ;

        return [
            new LayoutFields([
                FullNameField::buildField(
                    $formConfig['full_name_title']??'',
                    $formConfig['full_name_placeholder']??''
                ),
            ]),
            new SeparatorField('Дані про правопорушення'),

            new LayoutFields([
                new FineSeries('fine_series',
                    $formConfig['fine_series_title']??'',
                    $formConfig['fine_series_placeholder']??''
                ),
                new TextField('fine_number',
                    $formConfig['fine_number_title']??'',
                    $formConfig['fine_number_placeholder']??''
                ),
            ]),
            new LayoutFields([
                new SelectField('region',
                    $formConfig['region_title']??'',
                    $formConfig['region_placeholder']??'',
                    $regions
                ),
            ]),

            new LayoutFields([
                SumField::build(
                    $formConfig['sum_title']??'',
                    $formConfig['sum_placeholder']??''
                ),
                TotalCaptionField::build($formConfig['total_title']??'')
            ])

        ];
    }

    public function renderDataForPreview($fieldValues): array
    {

        return [
            'Прізвище, ім`я та по-батькові'=> $fieldValues['full_name'],
            'Дані про правопорушення'=> '',
            'Регіон'=> $fieldValues['region_title'],
            'Серія постанови'=> $fieldValues['fine_series'],
            'Номер постанови'=> $fieldValues['fine_number'],

        ];
    }
}