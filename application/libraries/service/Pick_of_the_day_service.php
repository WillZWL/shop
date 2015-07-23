<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Landpage_listing_service.php";

class Pick_of_the_day_service extends Landpage_listing_service
{
    private $type = 'PD';
    private $top_deals_service;
    private $latest_arrivals_service;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Product_service.php");
        $this->product_service = new Product_service();
        include_once(APPPATH . "libraries/service/Top_deals_service.php");
        $this->set_top_deals_service(new Top_deals_service());
        include_once(APPPATH . "libraries/service/Latest_arrivals_service.php");
        $this->set_latest_arrivals_service(new Latest_arrivals_service());
    }

    public function get_top_deals_service()
    {
        return $this->top_deals_service;
    }

    public function set_top_deals_service($srv = NULL)
    {
        $this->top_deals_service = $srv;
    }

    public function get_count($catid = "", $mode = "", $platform)
    {
        if ($catid == "") {
            return FALSE;
        } else {
            return $this->get_dao()->get_num_rows(array("catid" => $catid, "type" => 'PD', "mode" => $mode, "platform_id" => $platform));
        }
    }

    public function get_pick_of_the_day($catid = "", $rank = "", $platform = "")
    {
        if ($catid === "") {
            return $this->get_dao()->get();
        } else {
            //$obj =  $this->get_dao()->get(array("catid"=>$catid,"type"=>'PD',"rank"=>$rank));
            $obj = $this->get_dao()->get_item_by_rank($catid, "PD", $rank, $platform, "Product_list_w_name_dto");

            return $obj;
        }
    }

    public function insert($obj)
    {
        return $this->get_dao()->insert($obj);
    }

    public function update($obj)
    {
        return $this->get_dao()->update($obj);
    }

    public function get_product_list($where = array(), $option = array())
    {
        return $this->product_service->get_dao()->get_list_w_name($where, $option, "Product_list_w_name_dto");
    }

    public function get_product_list_total($where = array(), $option = array())
    {
        return $this->product_service->get_dao()->get_list_w_name($where, $option, "Product_list_w_name_dto");
    }

    public function delete_bs($where = array())
    {
        return $this->get_dao()->q_delete($where);
    }

    public function trans_start()
    {
        $this->get_dao()->trans_start();
    }

    public function trans_complete()
    {
        $this->get_dao()->trans_complete();
    }

    public function display_list($catid = "")
    {
        if ($catid == "") {
            return FALSE;
        } else {
            $manual = $this->get_list_w_name($catid, "M", "PD", PLATFORMID);
            $auto = $this->get_list_w_name($catid, "A", "PD", PLATFORMID);

            $ret = array();
            $added = array();
            foreach ($manual as $obj) {
                $ret[] = array("prodid" => $obj->get_selection(), "name" => $obj->get_name(), "image" => $obj->get_image(), "price" => $obj->get_price(), "website_status" => $obj->get_website_status(), "qty" => $obj->get_quantity(), "web_qty" => $obj->get_website_quantity());
                $added[] = $obj->get_selection();
            }

            $cnt = count($added);

            foreach ($auto as $obj) {
                if ($cnt < $this->get_limit() && !in_array($obj->get_selection, $added)) {
                    $ret[] = array("prodid" => $obj->get_selection(), "name" => $obj->get_name(), "image" => $obj->get_image(), "price" => $obj->get_price(), "website_status" => $obj->get_website_status(), "qty" => $obj->get_quantity(), "web_qty" => $obj->get_website_quantity());
                    $cnt++;
                }
            }

            $latest_arrvials_list = $this->get_latest_arrivals_service()->display_list($catid);
            /*foreach ($top_deals_list as $obj)
            {
                $ret[] = $obj;
            }*/
            return array_merge($ret, $latest_arrvials_list);
            //return $ret;
        }
    }

    public function get_list_w_name($catid, $mode = "M", $type = "PD", $platform, $rtype = "object")
    {
        return $this->get_dao()->get_list_w_pname($catid, $mode, $type, $platform, "Best_seller_prodname_dto", $rtype);
    }

    public function get_latest_arrivals_service()
    {
        return $this->latest_arrivals_service;
    }

    public function set_latest_arrivals_service($srv = NULL)
    {
        $this->latest_arrivals_service = $srv;
    }

    public function get_home_pick_of_the_day($platform = "")
    {
        return $this->product_service->get_pick_of_the_day_list_by_cat('', 0, 30, $this->get_limit(), $platform);
    }

    public function get_type()
    {
        return $this->type;
    }

    protected function _get_product_list_for_home($platform = "")
    {
        return $this->product_service->get_pick_of_the_day_list_by_cat('', 0, 30, $this->get_limit(), $platform);
    }

    protected function _get_product_list($filter_column = '', $cat_id = '', $platform = "")
    {
        return $this->product_service->get_pick_of_the_day_list_by_cat($filter_column, $cat_id, 30, $this->get_limit(), $platform);
    }
}


