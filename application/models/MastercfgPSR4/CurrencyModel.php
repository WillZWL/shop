<?php
namespace AtomV2\Models\Mastercfg;

use AtomV2\Service\CurrencyService;

class CurrencyModel extends \CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->currencyService = new CurrencyService;
    }

    public function getNameWIdKey()
    {
        return $this->currencyService->getNameWIdKey();
    }

    public function updateRoundUp(&$data)
    {
        foreach ($_POST["round_up"] as $currency_id => $round_up) {
            if (isset($data["currency_list"][$currency_id]) && $data["currency_list"][$currency_id]->getRoundUp() != $round_up) {
                $data["currency_list"][$currency_id]->setRoundUp($round_up);
                if (!$this->currencyService->update($data["currency_list"][$currency_id])) {
                    $_SESSION["NOTICE"] = "ERROR: " . str_replace(APPPATH, "", __FILE__) . "@" . __LINE__ . " " . $this->db->_error_message();
                    return FALSE;
                }
            }
        }
        return TRUE;
    }
}