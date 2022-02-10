<?php


namespace EvolutionCMS\Services\ServicesList\MVS\PoliceProtection;


use EvolutionCMS\Services\Contracts\IServiceAmount;
use EvolutionCMS\Services\Helpers\FieldHelpers;

class PoliceProtectionPaymentRecipients implements \EvolutionCMS\Services\Contracts\IPaymentRecipients
{

    public function getPaymentRecipients($formData): array
    {
        $kod = FieldHelpers::policeSecurityAccountParser($value);

        $kod_o = $kod['kod_o'];
        $kod_r = $kod['kod_r'];

//
//        if (mb_strlen($schet, "UTF-8") == 9 && substr($schet, 0, 2) == '62') {
//            $kod_o = mb_substr($schet, 0, 1, "UTF-8");
//            $kod_r = mb_substr($schet, 1, 2, "UTF-8");
//        } else{
//            $kod_o = mb_substr($schet, 0, 3, "UTF-8");
//            $kod_r = mb_substr($schet, 3, 2, "UTF-8");
//        }
//
//
//        $sql = "SELECT * FROM " . $modx->getFullTableName("poregcode_items") . " WHERE `kod_o`='" . $kod_o . "' AND `kod_r`='" . $kod_r . "' LIMIT 0,1";
//        $q = $modx->db->query($sql);
//        if ($modx->db->getRecordCount($q) == 1) {
//            $row = $modx->db->getRow($q);
//            $poluch['name'] = $row['name'];
//            $poluch['account'] = $row['iban'];
//            $poluch['egrpou'] = $row['okpo'];
//            $poluch['mfo'] = $row['mfo'];
//            if ($poluch['mfo']) {
//                $poluch['bank'] = $modx->db->getValue("SELECT name FROM " . $modx->getFullTableName("banks_items") . " WHERE `mfo`='" . $poluch['mfo'] . "' LIMIT 0,1");
//            }
//
//
        return [
          //  new
        ];
    }
}