<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Country_credit_card_service extends Base_service
{
    private $pmgw_card_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Country_credit_card_dao.php");
        $this->set_dao(new Country_credit_card_dao());
        include_once(APPPATH . "libraries/dao/Pmgw_card_dao.php");
        $this->set_pmgw_card_dao(new Pmgw_card_dao());
        include_once(APPPATH . "libraries/dao/Product_dao.php");
        $this->set_product_dao(new Product_dao());
        include_once(APPPATH . "libraries/service/Context_config_service.php");
        $this->config_service = new Context_config_service();
    }

    public function set_product_dao(Base_dao $dao)
    {
        $this->product_dao = $dao;
    }

    public function get_country_pmgw_card_list($where = array(), $option = array())
    {
        $altapay_limit_reach = $this->config_service->value_of("altapay_limit_reach");

        if ($this->cart_contain_apple_product(PLATFORMID)
            && $this->country_contain_global_collect(PLATFORMID, PLATFORMCOUNTRYID, $where["pp.sequence"])
        ) {
            if ($altapay_limit_reach)
                $where["pp.payment_gateway_id <> 'global_collect' && pp.payment_gateway_id <> 'altapay'"] = null;
            else
                $where["pp.payment_gateway_id <> 'global_collect'"] = null;
            if (isset($where["ccc.status"])) {
                unset($where["ccc.status"]);
                $where["(ccc.status=1 or ccc.forcing_with_condition=99)"] = null;
            }

            $where["pp.sequence in (99)"] = null;
            unset($where["pp.sequence"]);
            $option["groupby"] = "ccc.country_id, pc.code, pp.payment_gateway_id, pc.card_id, pp.status";
        } elseif ($altapay_limit_reach)
            $where["pp.payment_gateway_id <> 'altapay'"] = null;

        $result = $this->get_dao()->get_country_pmgw_card_list($where, $option);
//        print $this->get_dao()->db->last_query();
        return $result;
    }

    public function cart_contain_apple_product($platformId)
    {
        $productList = "";
        foreach ($_SESSION["cart"][$platformId] as $key => $value) {
            if ($productList != "")
                $productList .= ",";
            $productList .= "'" . $key . "'";
        }

        $where = array("sku in (" . $productList . ")" => null, "brand_id" => 8);
        $productList = $this->get_product_dao()->get_list($where, array("limit" => -1));
//        print $this->get_product_dao()->db->last_query();
        if ($productList) {
            foreach ($productList as $product) {
                return true;
            }
            return false;
        } else
            return false;
    }

    public function get_product_dao()
    {
        return $this->product_dao;
    }

    private function country_contain_global_collect($platform_id, $platform_country_id, $seq)
    {
        $where = array("pc.status" => 1
        , "ccc.status" => 1
        , "pg.status" => 1
        , "pp.platform_id" => $platform_id
        , "pp.status" => 1
        , "pp.sequence" => $seq
        , "ccc.country_id" => $platform_country_id
        , "pp.payment_gateway_id" => "global_collect");

        $option = array("groupby" => "pp.payment_gateway_id");
        $cardList = $this->get_dao()->get_country_pmgw_card_list($where, $option);
//        print $this->get_dao()->db->last_query();
        if ($cardList) {
            foreach ($cardList as $card) {
                return true;
            }
        }
        return false;
    }

    public function get_pmgw_card_dao()
    {
        return $this->pmgw_card_dao;
    }

    public function set_pmgw_card_dao(Base_dao $dao)
    {
        $this->pmgw_card_dao = $dao;
    }
}

/* End of file country_credit_card_service.php */
/* Location: ./system/application/libraries/service/Country_credit_card_service.php */