<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Price_service.php";

class Price_qoo10_service extends Price_service
{
    private $wms_wh_srv;

    public function __construct()
    {
        parent::__construct();
        $this->set_tool_path('marketing/pricing_tool_' . strtolower(PLATFORM_TYPE));

        include_once(APPPATH . "libraries/service/Wms_warehouse_service.php");
        $this->set_wms_wh_srv(new Wms_warehouse_service());
    }

    public function get_pricing_tool_info($platform_id = "", $sku = "")
    {
        $ret = parent::get_pricing_tool_info($platform_id, $sku);

        return $ret;

    }


    // SBF#2558 we override this function to specially calculate the way qoo10 structures their commission charges

    public function calc_commission($dto = NULL)
    {
        $this->init_dto($dto);

        # use a defaut of 10%
        # if we are 200 to 500, use 9%
        # if above 500, use 8%

        $percent = $this->determine_commission_percent($dto->get_price());
        $dto->set_platform_commission($percent * 100); #this is to allow header to reflect accurate percentage
        $dto->set_sales_commission(number_format(($dto->get_price() + $dto->get_delivery_charge()) * $percent, 2, ".", ""));
    }

    private function determine_commission_percent($price)
    {
        $percent = 0.10;
        if ($price >= 200 && $price <= 500) $percent = 0.09;
        if ($price > 500) $percent = 0.08;

        return $percent;
    }

    // public function get_wms_wh_srv()
    // {
    //  return $this->wms_wh_srv;
    // }

    // public function set_wms_wh_srv(Base_service $svc)
    // {
    //  $this->wms_wh_srv = $svc;
    // }

    // public function get_rrp_factor_by_sku($sku='')
    // {
    //  $default_rrp_factor = 1.34;
    //  $rrp_factor = NULL;

    //  if ($sku == '')
    //  {
    //      $rrp_factor = $default_rrp_factor;
    //  }
    //  else
    //  {
    //      $price_obj = $this->get(array('sku'=>$sku));
    //      if ($price_obj)
    //      {
    //          $rrp_factor = $price_obj->get_rrp_factor();
    //      }
    //      else
    //      {
    //          $product_obj = $this->get_product_service()->get(array('sku'=>$sku));

    //          $where = array();
    //          $where['p.cat_id'] = $product_obj->get_cat_id();
    //          $where['p.sub_cat_id'] = $product_obj->get_sub_cat_id();
    //          $where['p.sub_sub_cat_id'] = $product_obj->get_sub_sub_cat_id();
    //          $where['p.sku != '] = $sku;

    //          $list = $this->get_product_service()->get_list_having_price($where, array('limit'=>1));
    //          if (count($list) == 0)
    //          {
    //              $rrp_factor = $default_rrp_factor;
    //          }
    //          else
    //          {
    //              $product_obj = $list[0];
    //              $price_obj = $this->get(array('sku'=>$product_obj->get_sku()));
    //              if ($price_obj)
    //              {
    //                  $rrp_factor = $price_obj->get_rrp_factor();
    //              }
    //              else
    //              {
    //                  $rrp_factor = $default_rrp_factor;
    //              }
    //          }
    //      }
    //  }

    //  if (is_null($rrp_factor))
    //  {
    //      $rrp_factor = $default_rrp_factor;
    //  }

    //  return $rrp_factor;
    // }

    // public function update_rrp_factor()
    // {
    //  $price_list = $this->get_list(array('rrp_factor is NULL'=>NULL), array('limit'=>-1));
    //  foreach ($price_list as $price_obj)
    //  {
    //      $rrp_factor = $this->get_rrp_factor_by_sku($price_obj->get_sku());
    //      $price_obj->set_rrp_factor($rrp_factor);
    //      $this->update($price_obj);
    //  }
    //  return $this->get_dao()->update_rrp_factor();
    // }

    // public function is_display_saving_message($country_id=NULL)
    // {
    //  if (is_null($country_id))
    //  {
    //      $country_id = PLATFORMCOUNTRYID;
    //  }

    //  switch (strtolower($country_id))
    //  {
    //      case 'au' :
    //      case 'hk' :
    //      case 'my' :
    //      case 'nz' :
    //      case 'ph' :
    //      case 'sg' : $result = 'T'; break;

    //      default   : $result = 'F';
    //  }

    //  return $result;
    // }
}
/* End of file price_qoo10_service.php */
/* Location: ./system/application/libraries/service/Price_qoo10_service.php */
