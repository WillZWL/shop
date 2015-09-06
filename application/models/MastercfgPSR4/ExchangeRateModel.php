<?php
namespace AtomV2\Models\Mastercfg;

use AtomV2\Service\ExchangeRateService;
use AtomV2\Service\CurrencyService;

class ExchangeRateModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->exchangeRateService = new ExchangeRateService;
        $this->currencyService = new CurrencyService;
    }

    public function alterExchangeRate($from, $to, $rate, $platform = "")
    {
        if ($platform == "approval") {
            $dao = "ExchangeRateApprovalDao";
        } else {
            $dao = "ExchangeRateDao";
        }
        return $this->exchangeRateService->alterExchangeRate($from, $to, $rate, $dao);
    }

    public function getBasedRate($base, $currency_list, $platform = "")
    {
        if ($platform == "approval") {
            $dao = "ExchangeRateApprovalDao";
        } else {
            $dao = "ExchangeRateDao";
        }
        return $this->exchangeRateService->getBasedRate($base, $currency_list, $dao);
    }

    public function getBasedApprovalRate($base, $currency_list)
    {
        return $this->exchangeRateService->getBasedApprovalRate($base, $currency_list);
    }

    public function getCurrencyList($where = [], $option = [])
    {
        return $this->exchangeRateService->getCurrencyList($where, $option);
    }

    public function getActiveCurrencyList($where = [], $option = [])
    {
        return $this->exchangeRateService->getActiveCurrencyList($where, $option);
    }

    public function getActiveCurrencyObjList($where = [], $option = [])
    {
        return $this->exchangeRateService->getActiveCurrencyObjList($where, $option);
    }

    public function getCurrencyFullList($where = [], $option = [])
    {
        return $this->currencyService->getList($where, $option);
    }

    public function getExchangeRateApprovalList($where = [], $option = [])
    {
        return $this->exchangeRateService->getExchangeRateApprovalList($where, $option);
    }

    public function notificationEmail($sent_to, $value)
    {
        return $this->exchangeRateService->notificationEmail($sent_to, $value);
    }

    public function getSign($platform = "")
    {
        return $this->currencyService->getSign($platform);
    }

    public function uploadExchangeRate()
    {
        return $this->exchangeRateService->uploadExchangeRate();
    }

    public function updateExchangeRateFromCv()
    {
        return $this->exchangeRateService->updateExchangeRateFromCv();
    }

    public function compareDifference($from = "", $to = "", $rate = "")
    {
        return $this->exchangeRateService->compareDifference($from, $to, $rate);
    }

    public function currencyExchange($from_currency, $to_currency, $amount)
    {
        $exchange_rate = $this->getExchangeRate($from_currency, $to_currency);
        return $exchange_rate->get_rate() * $amount;
    }

    public function getExchangeRate($from = "", $to = "")
    {
        return $this->exchangeRateService->getExchangeRate($from, $to);
    }
}

?>