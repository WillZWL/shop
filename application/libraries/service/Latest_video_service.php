<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Landpage_video_listing_service.php";

class Latest_video_service extends Landpage_video_listing_service
{
    private $type = 'LV';

    public function __construct()
    {
        parent::Landpage_video_listing_service();
        include_once(APPPATH . "libraries/service/Product_service.php");
        $this->product_service = new Product_service();
    }

    public function get_count($catid = "", $mode = "", $platform = "", $type = "", $src = "")
    {
        if ($catid == "") {
            return FALSE;
        } else {
            return $this->get_dao()->get_num_rows(array("catid" => $catid, "listing_type" => 'LV', "mode" => $mode, "platform_id" => $platform, "video_type" => $type, "src" => $src));
        }
    }

    public function get_latest_video($catid = "", $rank = "", $v_type = "", $platform = "", $src = "")
    {
        if ($catid === "") {
            return $this->get_dao()->get();
        } else {
            //$obj =  $this->get_dao()->get(array("catid"=>$catid,"type"=>'TV',"rank"=>$rank));
            $obj = $this->get_dao()->get_item_by_rank($catid, "LV", $v_type, $rank, $platform, $src, "Video_list_w_name_dto");

            return $obj;
        }
    }

    public function get_video_list($where = array(), $option = array())
    {
        return $this->product_service->get_dao()->get_video_list_w_name($where, $option);
    }

    public function get_video_list_total($where = array(), $option = array())
    {
        return $this->product_service->get_dao()->get_video_list_w_name($where, $option);
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

    public function display_list($catid = "", $type = "", $src = "")
    {
        if ($catid == "") {
            return FALSE;
        } else {
            $ret = array();
            $added = array();

            if ($manual = $this->get_list_w_name($catid, "M", "LV", $type, PLATFORMID, $src)) {
                foreach ($manual as $obj) {
                    $ret[] = array("ref_id" => $obj->get_ref_id(), "sku" => $obj->get_sku(), "video_type" => $obj->get_video_type(), "video_src" => $obj->get_src(), "name" => $obj->get_name(), "image" => $obj->get_image(), "price" => $obj->get_price(), "website_status" => $obj->get_website_status(), "qty" => $obj->get_quantity(), "web_qty" => $obj->get_website_quantity());
                    $added[] = $obj->get_ref_id();
                }
            }

            $cnt = count($added);

            if ($auto = $this->get_list_w_name($catid, "A", "LV", $type, PLATFORMID, $src)) {
                foreach ($auto as $obj) {
                    if ($cnt < $this->get_limit() && !in_array($obj->get_ref_id(), $added)) {
                        $ret[] = array("ref_id" => $obj->get_ref_id(), "sku" => $obj->get_sku(), "video_type" => $obj->get_video_type(), "video_src" => $obj->get_src(), "name" => $obj->get_name(), "image" => $obj->get_image(), "price" => $obj->get_price(), "website_status" => $obj->get_website_status(), "qty" => $obj->get_quantity(), "web_qty" => $obj->get_website_quantity());
                        $cnt++;
                    }
                }
            }

            //$vzaar_backup_list = $this->vzaar_display_list($catid, $type);
            //return array_merge($ret, $vzaar_backup_list);

            return $ret;
        }
    }

    public function get_list_w_name($catid, $mode = "M", $l_type = "LV", $v_type = "", $platform = "", $src = "", $rtype = "object")
    {
        return $this->get_dao()->get_list_w_pname($catid, $mode, $l_type, $v_type, $platform, $src, $rtype, "top_view_video_prodname_dto");
    }

    public function vzaar_display_list($catid = "", $type = "")
    {
        if ($catid == "") {
            return FALSE;
        } else {
            $ret = array();
            $added = array();

            if ($manual = $this->get_list_w_name($catid, "M", "TV", $type, PLATFORMID, "V")) {
                foreach ($manual as $obj) {
                    $ret[] = array("ref_id" => $obj->get_ref_id(), "sku" => $obj->get_sku(), "video_type" => $obj->get_video_type(), "video_src" => $obj->get_src(), "name" => $obj->get_name(), "image" => $obj->get_image(), "price" => $obj->get_price(), "website_status" => $obj->get_website_status(), "qty" => $obj->get_quantity(), "web_qty" => $obj->get_website_quantity());
                    $added[] = $obj->get_ref_id();
                }
            }

            $cnt = count($added);

            if ($auto = $this->get_list_w_name($catid, "A", "TV", $type, PLATFORMID, "V")) {
                foreach ($auto as $obj) {
                    if ($cnt < $this->get_limit() && !in_array($obj->get_ref_id(), $added)) {
                        $ret[] = array("ref_id" => $obj->get_ref_id(), "sku" => $obj->get_sku(), "video_type" => $obj->get_video_type(), "video_src" => $obj->get_src(), "name" => $obj->get_name(), "image" => $obj->get_image(), "price" => $obj->get_price(), "website_status" => $obj->get_website_status(), "qty" => $obj->get_quantity(), "web_qty" => $obj->get_website_quantity());
                        $cnt++;
                    }
                }
            }

            return $ret;
        }
    }

    public function get_type()
    {
        return $this->type;
    }

    protected function _get_product_list_for_home($platform = "")
    {
        return $this->product_service->get_latest_video_list_by_cat('', 0, 30, $this->get_limit(), $platform);
    }

    protected function _get_video_list($filter_column = '', $cat_id = '', $platform = '', $video_type = '')
    {
        return $this->product_service->get_listed_video_list(
            array('product.status' => 2, 'product.website_status' => 'I', '(pr.price OR pr2.price) >' => '0', 'pr2.listing_status' => 'L',
                'product.website_quantity >' => 0, 'platform_biz_var.selling_platform_id' => $platform, 'product_video.type' => $video_type, 'product.' . $filter_column => $cat_id),
            array('orderby' => 'pv.create_on DESC', 'limit' => $this->get_limit()));
    }
}


