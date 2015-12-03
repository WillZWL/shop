<?php
namespace ESG\Panther\Service;

class PlatformBizVarService extends BaseService
{
    public function getPlatformBizVar($id)
    {
        if ($id != "") {
            $ret = $this->getDao('PlatformBizVar')->get(["selling_platform_id" => $id]);
        } else {
            $ret = $this->getDao('PlatformBizVar')->get();
        }
        return $ret;
    }

    public function getPlatformBizVarWithCountry($country = [])
    {
        return $this->getDao('PlatformBizVar')->getPlatformBizVarWithCountry($country = []);
    }

    public function getSellingPlatformList()
    {
        return $this->getDao('SellingPlatform')->getList([], ["limit" => -1]);
    }

    public function getCurrencyList()
    {
        $obj_array = $this->getDao('Currency')->getList([]);

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

        if ($objlist = $this->getDao('PlatformBizVar')->getList($where, ["limit" => -1])) {
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
        return $this->getDao('PlatformBizVar')->getListWithPlatformName($where, $option);
    }

    public function getPricingToolPlatformList($sku, $platform_type)
    {
        return $this->getDao('PlatformBizVar')->getPricingToolPlatformList($sku, $platform_type);
    }

    public function getListWithCountryName($where = [], $option = [])
    {
        return $this->getDao('PlatformBizVar')->getListWithCountryName($where, $option);
    }

    public function getUniqueDestCountryList()
    {
        return $this->getDao('PlatformBizVar')->getUniqueDestCountryList();
    }

    public function update($data, $where = [])
    {
     return $this->getDao('PlatformBizVar')->update($data);
    }

    public function getDestCountryWithDeliveryTypeList()
    {
        return $this->getDao('PlatformBizVar')->getDestCountryWithDeliveryTypeList();
    }

    public function getFreeDeliveryLimit($platform_id = "")
    {
        return $this->getDao('PlatformBizVar')->getFreeDeliveryLimit($platform_id);
    }

    public function loadVo()
    {
        $this->getDao('PlatformBizVar')->get();
    }

}
