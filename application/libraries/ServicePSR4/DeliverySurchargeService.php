<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\DeliverySurchargeDao;

class DeliverySurchargeService extends BaseService
{
    function __construct()
    {
        parent::__construct();
        $this->setDao(new DeliverySurchargeDao);
    }

    function getDelSurcharge($postcode, $deliveryCountryId, $state, $return_code_type = FALSE)
    {
        $surcharge = 0;
        $codeType = "";
        $currencyId = "";

        if ($deliveryCountryId && ($state || $postcode)) {
            $ar_where = array();
            $where["country_id"] = $deliveryCountryId;

            if ($state) {
                $ar_where[] = "(code_type = 'ST' AND code='{$state}')";
            }
            if ($postcode) {
                $postcode = preg_replace('/[^0-9a-z]/i', '', $postcode);
                $ar_where[] = "(code_type = 'PC' AND '{$postcode}' LIKE code)";
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
            if ($obj = $this->getDao("DeliverySurcharge")->getList($where, $option)) {
                $surcharge = $obj->getSurcharge();
                $codeType = $obj->getCodeType();
                $currencyId = $obj->getCurrencyId();
            }
        }

        return ["surcharge" => $surcharge, "code_type" => $codeType, "currency_id" => $currencyId];
    }
}


