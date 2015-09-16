<?php
namespace ESG\Panther\Models\Mastercfg;

class ExchangeRateModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function alterExchangeRate($from, $to, $rate, $platform = "")
    {
        if ($platform == "approval") {
            $dao = "ExchangeRateApproval";
        } else {
            $dao = "ExchangeRate";
        }
        return $this->sc['ExchangeRate']->alterExchangeRate($from, $to, $rate, $dao);
    }

    public function getBasedRate($base, $currency_list, $platform = "")
    {
        if ($platform == "approval") {
            $dao = "ExchangeRateApproval";
        } else {
            $dao = "ExchangeRate";
        }
        return $this->sc['ExchangeRate']->getBasedRate($base, $currency_list, $dao);
    }

    // public function getBasedApprovalRate($base, $currency_list)
    // {
    //     return $this->sc['ExchangeRate']->getBasedApprovalRate($base, $currency_list);
    // }

    public function getCurrencyList($where = [], $option = [])
    {
        return $this->sc['ExchangeRate']->getCurrencyList($where, $option);
    }

    public function getActiveCurrencyList($where = [], $option = [])
    {
        return $this->sc['ExchangeRate']->getActiveCurrencyList($where, $option);
    }

    public function getActiveCurrencyObjList($where = [], $option = [])
    {
        return $this->sc['ExchangeRate']->getActiveCurrencyObjList($where, $option);
    }

    public function getCurrencyFullList($where = [], $option = [])
    {
        return $this->getDao('currency')->getList($where, $option);
    }

    public function getExchangeRateApprovalList($where = [], $option = [])
    {
        return $this->sc['ExchangeRate']->getExchangeRateApprovalList($where, $option);
    }

    public function notificationEmail($sent_to, $value)
    {
        return $this->sc['ExchangeRate']->notificationEmail($sent_to, $value);
    }

    public function getSign($platform = "")
    {
        return $this->getDao('currency')->getSign($platform);
    }

    public function uploadExchangeRate()
    {
        return $this->sc['ExchangeRate']->uploadExchangeRate();
    }

    public function updateExchangeRateFromCv()
    {
        return $this->sc['ExchangeRate']->updateExchangeRateFromCv();
    }

    public function compareDifference($from = "", $to = "", $rate = "")
    {
        return $this->sc['ExchangeRate']->compareDifference($from, $to, $rate);
    }

    public function currencyExchange($from_currency, $to_currency, $amount)
    {
        $exchange_rate = $this->getExchangeRate($from_currency, $to_currency);
        return $exchange_rate->get_rate() * $amount;
    }

    public function getExchangeRate($from = "", $to = "")
    {
        return $this->sc['ExchangeRate']->getExchangeRate($from, $to);
    }
}

?>
