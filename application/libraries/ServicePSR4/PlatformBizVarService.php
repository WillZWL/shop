<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\PlatformBizVarDao;
use ESG\Panther\Dao\CurrencyDao;
use ESG\Panther\Dao\SellingPlatformDao;
use ESG\Panther\Dao\PlatformCourierDao;
use ESG\Panther\Dao\DeliveryTypeDao;

class PlatformBizVarService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();

        $this->setDao(new PlatformBizVarDao);
        $this->setPlatformBizVarDao(new PlatformBizVarDao);
        $this->setCurrencyDao(new CurrencyDao);
        $this->setSellingPlatformDao(new SellingPlatformDao);
        $this->setPlatformCourierDao(new PlatformCourierDao);
        $this->setDeliveryTypeDao(new DeliveryTypeDao);
    }

    public function getPlatformBizVar($id)
    {
        if ($id != "") {
            $ret = $this->getPlatformBizVarDao()->get(["selling_platform_id" => $id]);
        } else {
            $ret = $this->getPlatformBizVarDao()->get();
        }
        return $ret;
    }

    public function getPlatformBizVarWithCountry($country = [])
    {
        return $this->getPlatformBizVarDao()->getPlatformBizVarWithCountry($country = []);
    }

    public function getSellingPlatformList()
    {
        return $this->getSellingPlatformDao()->getList([], ["limit" => -1]);
    }

    public function getCurrencyList()
    {
        $obj_array = $this->getCurrencyDao()->getList([]);

        if ($obj_array !== FALSE) {
            $rtn = [];
            foreach ($obj_array as $obj) {
                $rtn[$obj->getCurrencyId()] = $obj->getName();
            }
        } else {
            $rtn = FALSE;
        }

        return $rtn;
    }

    public function preLoadPlatformCurrencyList($platform_id = NULL)
    {
        $data = [];
        $where = [];

        if (!is_null($platform_id)) {
            $where["selling_platform_id"] = $platform_id;
        }

        if ($objlist = $this->getDao()->getList($where, ["limit" => -1])) {
            foreach ($objlist as $obj) {
                $platform_id = $obj->getSellingPlatformId();
                $curr_id = $obj->getPlatformCurrencyId();
                if (isset($_SESSION["CURRENCY"][$curr_id])) {

                    $sign_pos = $obj->getSignPos();
                    $dec_place = $obj->getDecPlace();
                    $dec_point = $obj->getDecPoint();
                    $thousands_sep = $obj->getThousandsSep();

                    if (empty($sign_pos)) {
                        $sign_pos = $_SESSION["CURRENCY"][$curr_id]["sign_pos"];
                        $dec_place = $_SESSION["CURRENCY"][$curr_id]["dec_place"];
                        $dec_point = $_SESSION["CURRENCY"][$curr_id]["dec_point"];
                        $thousands_sep = $_SESSION["CURRENCY"][$curr_id]["thousands_sep"];
                    }

                    $data[$platform_id] = [
                        "currency_id" => $curr_id,
                        "sign" => $_SESSION["CURRENCY"][$curr_id]["sign"],
                        "sign_pos" => $obj->getSignPos(),
                        "dec_place" => $obj->getDecPlace(),
                        "dec_point" => $obj->getDecPoint(),
                        "thousands_sep" => $obj->getThousandsSep()
                    ];
                } else {
                    $data[$platform_id] = [
                        "currency_id" => $curr_id,
                        "sign" => null,
                        "sign_pos" => $obj->getSignPos(),
                        "dec_place" => $obj->getDecPlace(),
                        "dec_point" => $obj->getDecPoint(),
                        "thousands_sep" => $obj->getThousandsSep()
                    ];
                }
            }
        }
        return $data;
    }

    public function getListWithPlatformName($where = [], $option = [])
    {
        return $this->getDao()->getListWithPlatformName($where, $option);
    }

    public function getPricingToolPlatformList($sku, $platform_type)
    {
        return $this->getDao()->getPricingToolPlatformList($sku, $platform_type);
    }

    public function getListWithCountryName($where = [], $option = [])
    {
        return $this->getDao()->getListWithCountryName($where, $option);
    }

    public function getUniqueDestCountryList()
    {
        return $this->getDao()->getUniqueDestCountryList();
    }

    public function update($data, $where = [])
    {
     return $this->getPlatformBizVarDao()->update($data);
    }

    public function getDestCountryWithDeliveryTypeList()
    {
        return $this->getDao()->getDestCountryWithDeliveryTypeList();
    }

    public function getFreeDeliveryLimit($platform_id = "")
    {
        return $this->getDao()->getFreeDeliveryLimit($platform_id);
    }

    public function loadVo()
    {
        $this->getPlatformBizVarDao()->get();
    }

    public function setPlatformBizVarDao($value)
    {
        $this->platformBizVarDao = $value;
    }

    public function getPlatformBizVarDao()
    {
        return $this->platformBizVarDao;
    }

    public function setCurrencyDao($value)
    {
        $this->currencyDao = $value;
    }

    public function getCurrencyDao()
    {
        return $this->currencyDao;
    }

    public function setSellingPlatformDao($value)
    {
        $this->sellingPlatformDao = $value;
    }

    public function getSellingPlatformDao()
    {
        return $this->sellingPlatformDao;
    }

    public function setPlatformCourierDao($value)
    {
        $this->platformCourierDao = $value;
    }

    public function getPlatformCourierDao()
    {
        return $this->platformCourierDao;
    }

    public function setDeliveryTypeDao($value)
    {
        $this->deliveryTypeDao = $value;
    }

    public function getDeliveryTypeDao()
    {
        return $this->deliveryTypeDao;
    }
}
