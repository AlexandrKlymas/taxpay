<?php
namespace EvolutionCMS\Main\Services\PaymentsToBankSender\Repository;


use EvolutionCMS\Main\Services\PaymentsToBankSender\Exceptions\FileNotPutException;

class BankTKFtpRepository
{

    private $ftpServer;
    private $ftpUser;
    private $ftpPass;


    public function __construct()
    {
        $this->ftpServer = evo()->getConfig('g_bank_ftp_server');
        $this->ftpUser = evo()->getConfig('g_bank_ftp_user');
        $this->ftpPass = evo()->getConfig('g_bank_ftp_pass');
    }

    public function downloadFileFormBank($localFile,$remoteFileName){
        $connectionId = $this->connect();
        $remotePath = '/OUT/GOSPAY/';

        $remoteFile = $remotePath . $remoteFileName;
        ftp_pasv($connectionId, true);
        $remoteFiles = ftp_nlist($connectionId, $remotePath);
        if (!in_array($remoteFile, $remoteFiles)) {
                throw new \Exception('Файл ' . $remoteFileName . ' не найден на фтп-сервере по адресу ' . $remotePath);
        }
        $handle = @fopen(MODX_BASE_PATH.$localFile, "w");
        if (!@ftp_fget($connectionId, $handle, $remoteFile, FTP_ASCII)) {
            throw new \Exception('Файл ' . $remoteFileName . ' не удалось загрузить');
        }


    }

    public function sendFileToBank($localFile, $remoteFile,$sendToBank)
    {

        $connectionId = $this->connect();

        if(!$fp = fopen($localFile, "r")){
            throw new \Exception('EXPORT: Can not open file');
        }

        if($sendToBank !== true){
            return true;
        }
        if(!ftp_fput($connectionId, $remoteFile, $fp, FTP_BINARY)){
            throw new FileNotPutException('EXPORT: Can not send file to bank');
        }

        return true;
    }

    private function connect()
    {
        if(!$connectionId = ftp_connect($this->ftpServer)){
            throw new \Exception( 'EXPORT: Couldn`t connect to ' . $this->ftpServer, 'Couldn`t connect as ftp_user');
        }

        if(!ftp_login($connectionId, $this->ftpUser, $this->ftpPass)){
            throw new \Exception('EXPORT: Couldn`t connect as ' . $this->ftpServer, 'Couldn`t connect as ' . $this->ftpUser);
        }
        if(!ftp_pasv($connectionId, TRUE)){
            throw new \Exception('EXPORT: Can not set passive mode');
        }

        return $connectionId;

    }
}