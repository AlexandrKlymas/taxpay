<?php
namespace EvolutionCMS\Main\Services\PaymentsToBankSender\Repository;


class PaymentSenderExportFileRepository
{
    private $time;
    /**
     * @var string
     */
    private $archiveFolder;


   private $filesFolder = 'assets/files/exchange/bank/export/';

   private $badFolder = 'assets/files/exchange/bank/export/bad/';



    public function __construct($time)
    {
        $this->time = $time;
        $this->initArchiveFolder();
    }

    private function initArchiveFolder()
    {
        $archiveFolder = $this->filesFolder.'arc/b' . date("Y",$this->time) . '/b' . date("Y",$this->time) . '.' . date("m",$this->time) . '/';

        if (!is_dir(MODX_BASE_PATH.$archiveFolder)) {
            if(!mkdir(MODX_BASE_PATH.$archiveFolder,0775,true)){
                throw new \Exception('Can not create directory');
            }
        }
        $this->archiveFolder = $archiveFolder;
    }


    public function getTransactionFileName(){

        $startNumber = evo()->getConfig('g_bank_file_number_export');

        //паттерн для подсчета порядкового номера в имени файла
        $searchNextFileSequenceNumberPattern = $this->archiveFolder . 'GP' . date("dmy", time()) . '.*';
        $todayNextFileSequenceNumber = glob($searchNextFileSequenceNumberPattern);


        $todayNextFileSequenceNumber = count($todayNextFileSequenceNumber);

        if(!empty($startNumber)){
            $todayNextFileSequenceNumber += intval($startNumber);
        }

        $NNN = str_pad($todayNextFileSequenceNumber + 1, 3, '0', STR_PAD_LEFT);




        return 'GP' . date("dmy", time()) . '.' . $NNN;
    }

    public function createTransactionFile(string $transactionStrings)
    {
        $filePath = $this->filesFolder . $this->getTransactionFileName();

        $fp = fopen(MODX_BASE_PATH.$filePath, "w");

        if (!$fp) {
            throw new \Exception('Can not open file');
        }
        if(!fwrite($fp, $transactionStrings)){
            throw new \Exception('Can not write to  file');
        }
        fclose($fp);

        return $filePath;
    }

    public function archiveFile(string $transactionFileName)
    {
        @rename($this->filesFolder. $transactionFileName, $this->archiveFolder.$transactionFileName);
    }

    public function moveFileToBad(string $transactionFileName)
    {
        @rename($this->filesFolder. $transactionFileName, $this->badFolder.$transactionFileName);
    }
}