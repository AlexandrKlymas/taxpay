<?php
namespace EvolutionCMS\Main\Services\PaymentsToBankSender\Repository;


class PaymentSenderImportFileRepository
{
    private $time;
    /**
     * @var string
     */

    public function __construct($time)
    {
        $this->time = $time;
        $this->initFolders();

    }

    public function getImportFileName(){
        return '_g' . date("dmy",$this->time) . '.gp2';
    }
    public function getImportFilesFolder(){
        return 'assets/files/exchange/bank/import/';
    }
    public function getImportArchiveFilesFolder(){
        return $this->getImportFilesFolder().'arc/';
    }

    public function getImportBadFilesFolder(){
        return $this->getImportFilesFolder().'bad/';
    }


    public function getImportFilePath(){
        return $this->getImportFilesFolder().$this->getImportFileName();
    }

    public function archiveFile($fileName)
    {
        @rename(MODX_BASE_PATH . $this->getImportFilesFolder() . $fileName, MODX_BASE_PATH . $this->getImportArchiveFilesFolder() . $fileName);
    }

    public function moveFileToErrorFolder(string $importFileName)
    {
        @rename(MODX_BASE_PATH . $this->getImportFilesFolder() . $importFileName, MODX_BASE_PATH . $this->getImportBadFilesFolder() . $importFileName);
    }

    public function writeErrorsToFile($importFileName,array $errors)
    {
        $err = implode("\n", $errors);
        $fp = @fopen(MODX_BASE_PATH . $this->getImportBadFilesFolder() . $importFileName . ".err", "w");
        fwrite($fp, iconv("UTF-8", "cp1251", $err));
        fclose($fp);
    }

    private function initFolders()
    {
        $importFolder = $this->getImportFilesFolder();


        if(!is_dir($importFolder)){
            mkdir(MODX_BASE_PATH.$importFolder,0775);
        }

        $importArchiveFolder = $this->getImportArchiveFilesFolder();

        if(!is_dir($importArchiveFolder)){
            mkdir(MODX_BASE_PATH.$importArchiveFolder,0775);
        }

        $importBadFolder = $this->getImportBadFilesFolder();
        if(!is_dir($importBadFolder)){
            mkdir(MODX_BASE_PATH.$importBadFolder,0775);
        }

    }
}