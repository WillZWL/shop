<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Landpage_listing_service.php";

class Latest_arrivals_service extends Landpage_listing_service
{
    private $type = 'LA';

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/service/Product_service.php");
        $this->product_service = new Product_service();
    }

    public function get_count($catid = "", $mode = "", $platform = "")
    {
        if ($catid === "") {
            return FALSE;
        } else {
            return $this->get_dao()->get_num_rows(array("catid" => $catid, "type" => $this->get_type(), "mode" => $mode, "platform_id" => $platform));
        }
    }

    public function get_type()
    {
        return $this->type;
    }

    public function get_latest_arrivals($catid = "", $rank = "", $platform = "")
    {
        if ($catid === "") {
            return $this->get_dao()->get();
        } else {
            $obj = $this->get_dao()->get_item_by_rank($catid, $this->get_type(), $rank, $platform, "Product_list_w_name_dto");
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

    public function get_product_list_total($where = array(), $option = array("num_rows" => 1))
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
            $ret = array();
            $added = array();
            if ($manual = $this->get_list_w_name($catid, "M", $this->get_type(), PLATFORMID)) {
                foreach ($manual as $obj) {
                    $ret[] = array("prodid" => $obj->get_selection(), "name" => $obj->get_name(), "image" => $obj->get_image(), "price" => $obj->get_price(), "website_status" => $obj->get_website_status(), "qty" => $obj->get_quantity(), "web_qty" => $obj->get_website_quantity());
                    $added[] = $obj->get_selection();
                }
            }

            $cnt = count($added);

            if ($auto = $this->get_list_w_name($catid, "A", $this->get_type(), PLATFORMID)) {
                foreach ($auto as $obj) {
                    if ($cnt < $this->get_limit() && !in_array($obj->get_selection, $added)) {
                        $ret[] = array("prodid" => $obj->get_selection(), "name" => $obj->get_name(), "image" => $obj->get_image(), "price" => $obj->get_price(), "website_status" => $obj->get_website_status(), "qty" => $obj->get_quantity(), "web_qty" => $obj->get_website_quantity());
                        $cnt++;
                    }
                }
            }

            return $ret;
        }
    }

    public function get_list_w_name($catid, $mode = "M", $type = "LA", $platform, $rtype = "object")
    {
        return $this->get_dao()->get_list_w_pname($catid, $mode, $type, $platform, "latest_arrivals_prodname_dto", $rtype);
    }

    public function get_home_latest_arrivals($platform = "")
    {
        return $this->product_service->get_latest_arrivals_list_by_cat('', 0, 30, $this->get_limit(), $platform);
    }

    public function get_home_latest_arrival_grid_info($platform_id = "")
    {
        return $this->product_service->get_home_latest_arrival_grid_info($platform_id);
    }

    protected function _get_product_list_for_home()
    {
        return $this->product_service->get_list_having_price(
            array('product.status' => 2, 'product.website_status' => 'I',
                'product.website_quantity >' => 0, 'price.platform_id' => 'WSGB'),
            array('orderby' => 'p.create_on DESC', 'limit' => $this->get_limit()));
    }

    protected function _get_product_list($filter_column = '', $cat_id = '', $platform_id = '')
    {
        return $this->product_service->get_list_having_price(
            array('product.status' => 2, 'product.website_status' => 'I',
                'product.website_quantity >' => 0, 'price.platform_id' => "$platform_id", 'product.' . $filter_column => $cat_id),
            array('orderby' => 'p.create_on DESC', 'limit' => $this->get_limit()));

    }
}


?>