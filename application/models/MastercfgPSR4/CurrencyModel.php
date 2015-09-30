<?php
namespace ESG\Panther\Models\Mastercfg;

use ESG\Panther\Service\CurrencyService;

class CurrencyModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->currencyService = new CurrencyService;
    }

    public function getNameWIdKey()
    {
        return $this->currencyService->getNameWithIdKey();
    }

    public function updateRoundUp(&$data)
    {
        foreach ($_POST["round_up"] as $currency_id => $round_up) {
            if (isset($data["currency_list"][$currency_id]) && $data["currency_list"][$currency_id]->getRoundUp() != $round_up) {
                $data["currency_list"][$currency_id]->setRoundUp($round_up);
                if (!$this->currencyService->getDao('Currency')->update($data["currency_list"][$currency_id])) {
                    $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                    return FALSE;
                }
            }
        }

        return TRUE;
    }
}
