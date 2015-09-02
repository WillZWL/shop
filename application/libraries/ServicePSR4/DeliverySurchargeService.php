<?php
namespace AtomV2\Service;

use AtomV2\Dao\DeliverySurchargeDao;

class DeliverySurchargeService extends BaseService
{
    public $delivery_country_id;
    public $delivery_state;
    public $delivery_postcode;

    function __construct()
    {
        parent::__construct();
        $this->setDao(new DeliverySurchargeDao);
    }

    function getDelSurcharge($return_code_type = FALSE)
    {
        $surcharge = 0;
        $code_type = "";

        if ($this->delivery_country_id && ($this->delivery_state || $this->delivery_postcode)) {
            $ar_where = array();
            $where["country_id"] = $this->delivery_country_id;

            if ($this->delivery_state) {
                $ar_where[] = "(code_type = 'ST' AND code='{$this->delivery_state}')";
            }
            if ($this->delivery_postcode) {
                $this->delivery_postcode = preg_replace('/[^0-9a-z]/i', '', $this->delivery_postcode);
                $ar_where[] = "(code_type = 'PC' AND '{$this->delivery_postcode}' LIKE code)";
            }

            if ($ar_where) {
                $where_str = implode(" OR ", $ar_where);
                if (count($ar_where) > 1) {
                    $where_str = "(" . $where_str . ")";
                }
            }

            $where[$where_str] = NULL;

            $option["orderby"] = "surcharge DESC";
            $option["limit"] = 1;
            if ($obj = $this->get_list($where, $option)) {
                $surcharge = $obj->get_surcharge();
                $code_type = $obj->get_code_type();
                $currency_id = $obj->get_currency_id();
            }
        }
        if ($return_code_type) {
            return array("surcharge" => $surcharge, "code_type" => $code_type, "currency_id" => $currency_id);
        } else {
            return $surcharge;
        }
    }
}


