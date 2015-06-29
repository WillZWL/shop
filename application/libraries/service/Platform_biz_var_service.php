<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Platform_biz_var_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();

        include_once(APPPATH."libraries/dao/Platform_biz_var_dao.php");
        $this->set_dao(new Platform_biz_var_dao());
        $this->platform_biz_var_dao = new Platform_biz_var_dao();

        include_once(APPPATH."libraries/dao/Currency_dao.php");
        $this->currency_dao = new Currency_dao();

        include_once(APPPATH."libraries/dao/Selling_platform_dao.php");
        $this->selling_platform_dao = new Selling_platform_dao();

        include_once(APPPATH."libraries/dao/Platform_courier_dao.php");
        $this->set_pc_dao(new Platform_courier_dao());

        include_once(APPPATH."libraries/dao/Delivery_type_dao.php");
        $this->set_dt_dao(new Delivery_type_dao());
    }

    public function get_platform_biz_var($id)
    {
        if($id != "")
        {
            $ret = $this->platform_biz_var_dao->get(array("selling_platform_id"=>$id));
        }
        else
        {
            $ret = $this->platform_biz_var_dao->get();
        }
        return $ret;
    }

    public function get_platform_biz_var_w_country($country=array())
    {
        return $this->platform_biz_var_dao->get_platform_biz_var_w_country($country=array());
    }

    public function get_selling_platform_list()
    {
        return $this->selling_platform_dao->get_list(array(), array("limit"=>-1));
    }

    public function get_currency_list()
    {
        $obj_array = $this->currency_dao->get_list(array());

        if($obj_array !== FALSE)
        {
            $rtn = array();
            foreach($obj_array as $obj)
            {
                $rtn[$obj->get_id()] = $obj->get_name();
            }
        }
        else
        {
            $rtn = FALSE;
        }

        return $rtn;
    }

    public function pre_load_platform_currency_list($platform_id = NULL)
    {
        $data = array();
        $where = array();

        if (!is_null($platform_id))
        {
            $where["selling_platform_id"] = $platform_id;
        }

        if ($objlist = $this->get_list($where, array("limit"=>-1)))
        {
            foreach ($objlist as $obj)
            {
                $platform_id = $obj->get_selling_platform_id();
                $curr_id = $obj->get_platform_currency_id();
                if (isset($_SESSION["CURRENCY"][$curr_id]))
                {

                    $sign_pos = $obj->get_sign_pos();
                    $dec_place = $obj->get_dec_place();
                    $dec_point = $obj->get_dec_point();
                    $thousands_sep = $obj->get_thousands_sep();

                    if (empty($sign_pos))
                    {
                        $sign_pos = $_SESSION["CURRENCY"][$curr_id]["sign_pos"];
                        $dec_place = $_SESSION["CURRENCY"][$curr_id]["dec_place"];
                        $dec_point = $_SESSION["CURRENCY"][$curr_id]["dec_point"];
                        $thousands_sep = $_SESSION["CURRENCY"][$curr_id]["thousands_sep"];
                    }

                    $data[$platform_id] = array(
                                        "currency_id" => $curr_id,
                                        "sign" => $_SESSION["CURRENCY"][$curr_id]["sign"],
                                        "sign_pos" => $obj->get_sign_pos(),
                                        "dec_place" => $obj->get_dec_place(),
                                        "dec_point" => $obj->get_dec_point(),
                                        "thousands_sep" => $obj->get_thousands_sep()
                                        );
                }
                else
                {
                    $data[$platform_id] = array(
                                        "currency_id" => $curr_id,
                                        "sign" => null,
                                        "sign_pos" => $obj->get_sign_pos(),
                                        "dec_place" => $obj->get_dec_place(),
                                        "dec_point" => $obj->get_dec_point(),
                                        "thousands_sep" => $obj->get_thousands_sep()
                                        );
                }
            }
        }
        return $data;
    }

    public function get_list_w_platform_name($where = array(), $option = array())
    {
        return $this->get_dao()->get_list_w_platform_name($where, $option);
    }

    public function get_pricing_tool_platform_list($sku, $platform_type)
    {
        return $this->get_dao()->get_pricing_tool_platform_list($sku, $platform_type);
    }

    public function get_list_w_country_name($where = array(), $option = array())
    {
        return $this->get_dao()->get_list_w_country_name($where, $option);
    }

    public function get_unique_dest_country_list()
    {
        return $this->get_dao()->get_unique_dest_country_list();
    }

    public function get_dest_country_w_delivery_type_list()
    {
        return $this->get_dao()->get_dest_country_w_delivery_type_list();
    }

    public function get_free_delivery_limit($platform_id = "")
    {
        return $this->get_dao()->get_free_delivery_limit($platform_id);
    }

    // public function update($data, $where = array())
    // {
    //  return $this->platform_biz_var_dao->update($data);
    // }

    public function load_vo()
    {
        $this->platform_biz_var_dao->include_vo();
    }

    public function get_pc_dao()
    {
        return $this->pc_dao;
    }

    public function set_pc_dao($value)
    {
        $this->pc_dao = $value;
    }

    public function get_dt_dao()
    {
        return $this->dt_dao;
    }

    public function set_dt_dao($value)
    {
        $this->dt_dao = $value;
    }
}
