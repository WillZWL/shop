<?php
namespace AtomV2\Service;

use AtomV2\Dao\PlatformBizVarDao;
use AtomV2\Dao\CurrencyDao;
use AtomV2\Dao\SellingPlatformDao;
use AtomV2\Dao\PlatformCourierDao;
use AtomV2\Dao\DeliveryTypeDao;

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

    public function get_platform_biz_var($id)
    {
        if ($id != "") {
            $ret = $this->getPlatformBizVarDao()->get(["selling_platform_id" => $id]);
        } else {
            $ret = $this->getPlatformBizVarDao()->get();
        }
        return $ret;
    }

    public function get_platform_biz_var_w_country($country = [])
    {
        return $this->getPlatformBizVarDao()->get_platform_biz_var_w_country($country = []);
    }

    public function get_selling_platform_list()
    {
        return $this->getSellingPlatformDao()->getList([], ["limit" => -1]);
    }

    public function get_currency_list()
    {
        $obj_array = $this->getCurrencyDao()->getList([]);

        if ($obj_array !== FALSE) {
            $rtn = [];
            foreach ($obj_array as $obj) {
                $rtn[$obj->get_id()] = $obj->get_name();
            }
        } else {
            $rtn = FALSE;
        }

        return $rtn;
    }

    public function pre_load_platform_currency_list($platform_id = NULL)
    {
        $data = [];
        $where = [];

        if (!is_null($platform_id)) {
            $where["selling_platform_id"] = $platform_id;
        }

        if ($objlist = $this->getDao()->getList($where, ["limit" => -1])) {
            foreach ($objlist as $obj) {
                $platform_id = $obj->get_selling_platform_id();
                $curr_id = $obj->get_platform_currency_id();
                if (isset($_SESSION["CURRENCY"][$curr_id])) {

                    $sign_pos = $obj->get_sign_pos();
                    $dec_place = $obj->get_dec_place();
                    $dec_point = $obj->get_dec_point();
                    $thousands_sep = $obj->get_thousands_sep();

                    if (empty($sign_pos)) {
                        $sign_pos = $_SESSION["CURRENCY"][$curr_id]["sign_pos"];
                        $dec_place = $_SESSION["CURRENCY"][$curr_id]["dec_place"];
                        $dec_point = $_SESSION["CURRENCY"][$curr_id]["dec_point"];
                        $thousands_sep = $_SESSION["CURRENCY"][$curr_id]["thousands_sep"];
                    }

                    $data[$platform_id] = [
                        "currency_id" => $curr_id,
                        "sign" => $_SESSION["CURRENCY"][$curr_id]["sign"],
                        "sign_pos" => $obj->get_sign_pos(),
                        "dec_place" => $obj->get_dec_place(),
                        "dec_point" => $obj->get_dec_point(),
                        "thousands_sep" => $obj->get_thousands_sep()
                    ];
                } else {
                    $data[$platform_id] = [
                        "currency_id" => $curr_id,
                        "sign" => null,
                        "sign_pos" => $obj->get_sign_pos(),
                        "dec_place" => $obj->get_dec_place(),
                        "dec_point" => $obj->get_dec_point(),
                        "thousands_sep" => $obj->get_thousands_sep()
                    ];
                }
            }
        }
        return $data;
    }

    public function get_list_w_platform_name($where = [], $option = [])
    {
        return $this->getDao()->get_list_w_platform_name($where, $option);
    }

    public function get_pricing_tool_platform_list($sku, $platform_type)
    {
        return $this->getDao()->get_pricing_tool_platform_list($sku, $platform_type);
    }

    public function get_list_w_country_name($where = [], $option = [])
    {
        return $this->getDao()->get_list_w_country_name($where, $option);
    }

    public function get_unique_dest_country_list()
    {
        return $this->getDao()->get_unique_dest_country_list();
    }

    // public function update($data, $where = [])
    // {
    //  return $this->getPlatformBizVarDao()->update($data);
    // }

    public function get_dest_country_w_delivery_type_list()
    {
        return $this->getDao()->get_dest_country_w_delivery_type_list();
    }

    public function get_free_delivery_limit($platform_id = "")
    {
        return $this->getDao()->get_free_delivery_limit($platform_id);
    }

    public function load_vo()
    {
        $this->getPlatformBizVarDao()->include_vo();
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
