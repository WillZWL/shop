<?php
use AtomV2\Models\Mastercfg\ExchangeRateModel;

class Cron_exchange_rate extends MY_Controller
{
    private $appId = "CRN0006";

    function __construct()
    {
        parent::__construct();
        $this->exchangeRateModel = new ExchangeRateModel;
    }

    function index()
    {
        $this->uploadExchangeRate();
    }

    function uploadExchangeRate()
    {
        $this->exchangeRateModel->uploadExchangeRate();
    }

    function updateExchangeRateFromCv()
    {
        $this->exchangeRateModel->updateExchangeRateFromCv();
    }

    public function getAppId()
    {
        return $this->appId;
    }
}



