<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Festive_deal_service extends Base_service
{

    private $fds_dao;
    private $fdsc_dao;
    private $fdssc_dao;
    private $fdi_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/Festive_deal_dao.php");
        $this->set_dao(new Festive_deal_dao());
        include_once(APPPATH . "libraries/dao/Fd_section_dao.php");
        $this->set_fds_dao(new Fd_section_dao());
        include_once(APPPATH . "libraries/dao/Fd_section_cat_dao.php");
        $this->set_fdsc_dao(new Fd_section_cat_dao());
        include_once(APPPATH . "libraries/dao/Fd_section_sub_cat_dao.php");
        $this->set_fdssc_dao(new Fd_section_sub_cat_dao());
        include_once(APPPATH . "libraries/dao/Fd_item_dao.php");
        $this->set_fdi_dao(new Fd_item_dao());
    }

    public function set_dao(Base_dao $dao)
    {
        $this->br_dao = $dao;
    }

    public function get_fds_detail(Fd_section_vo $vo, $image_path)
    {
        if (!$vo) {
            return FALSE;
        } else {
            $ret = array();
            $ret["title_image"] = $vo->get_fd_image();
            $ret["list"] = $this->get_fdsc_detail($vo, $image_path);

            return $ret;
        }
    }

    public function get_fdsc_detail(Fd_section_vo $vo, $image_path)
    {
        if (!$vo) {
            return FALSE;
        } else {
            $ret = array();
            $fdsc_list = $this->get_fdsc_dao()->get_list(array("fds_id" => $vo->get_id()), array("orderby" => "display_order ASC"));
            if (count((array)$fdsc_list)) {
                foreach ($fdsc_list as $obj) {
                    $ret[] = array("img" => $image_path . $obj->get_image(), "link_id" => $obj->get_id());
                }
            }
            return $ret;
        }
    }

    public function get_fdsc_dao()
    {
        return $this->fdsc_dao;
    }

    public function set_fdsc_dao(Base_dao $dao)
    {
        $this->fdsc_dao = $dao;
    }

    public function get_fdssc_detail($list = array())
    {
        $ret = array();
        if (count($list)) {
            foreach ($list as $obj) {
                $result["image_left"] = $obj->get_left_image();
                $result["image_bg"] = $obj->get_bg_image();
                $result["image_right"] = $obj->get_right_image();
                $result["right_link"] = $obj->get_right_link();
                $result["prod_list"] = $this->get_product_list_detail($obj);

                $ret[] = $result;
            }
        }
        return $ret;

    }

    public function get_product_list_detail(Fd_section_sub_cat_vo $obj, $platform = "WSGB", $lang = "en")
    {
        $list = $this->get_fdi_dao()->get_product_list_with_prod_detail($obj->get_id(), $platform, $lang);

        $limit = 10;

        $rilist = array();

        $rolist = array();

        foreach ($list as $prod) {
            if ($prod->get_website_quantity() && $prod->get_website_status() == 'I') {
                if ($limit) {
                    $rilist[] = $prod;
                    $limit--;
                }
            } else {
                $rolist[] = $prod;
            }
        }

        if ($limit && count($rilist)) {
            for ($j = 0; $j < $limit; $j++) {
                $rilist[] = $rolist[$j];
            }
        }

        return $rilist;
    }

    public function get_fdi_dao()
    {
        return $this->fdi_dao;
    }

    public function set_fdi_dao(Base_dao $dao)
    {
        $this->fdi_dao = $dao;
    }

    public function verify_festive_link_id($festive = "", $link_id = "")
    {
        return $this->get_fdssc_dao()->verify_festive_link_id($festive, $link_id);
    }

    public function get_fdssc_dao()
    {
        return $this->fdssc_dao;
    }

    public function set_fdssc_dao(Base_dao $dao)
    {
        $this->fdssc_dao = $dao;
    }

    public function get_dao()
    {
        return $this->br_dao;
    }

    public function get_fds_dao()
    {
        return $this->fds_dao;
    }

    public function set_fds_dao(Base_dao $dao)
    {
        $this->fds_dao = $dao;
    }
}



