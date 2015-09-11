<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Banner_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        $this->load = $CI->load;
        $this->load->library('service/category_service');
        include_once(APPPATH . "libraries/dao/Banner_dao.php");
        $this->set_dao(new Banner_dao());
    }

    // public function get($value="")
    // {
    //  if($value != "") {
    //      $ret = $this->get_dao()->get(array("cat_id"=>$value));
    //  } else {
    //      $ret = $this->get_dao()->get();
    //  }

    //  return $ret;
    // }

    public function get_list_with_name($catid, $value)
    {
        return $this->get_dao()->get_list_with_name($catid, $value);
    }

    public function get_banner_list($where, $option)
    {
        $obj_list = $this->get_dao()->get_list($where, $option);
        $ret = array();
        if (count($obj_list)) {
            foreach ($obj_list as $obj) {
                $ret[$obj->get_type()] = $obj;
            }
        }
        return $ret;
    }

    public function update_flash($catid, $type, $usage)
    {
        $obj = $this->get_dao()->get(array("cat_id" => $catid, "type" => $type, "usage" => $usage));
        if (empty($obj)) {
            return TRUE;
        }
        $obj->set_flash_file("");
        return $this->get_dao()->update($obj);
    }

    public function get_banner_for_landpage($catid, $type)
    {
        $obj = $this->category_service->get_dao()->get(array("id" => $catid));
        $image = "";
        $file = "";

        $bobj = $this->get_dao()->get(array("id" => $catid, "type" => $type, "usage" => "PB"));
        if (!empty($bobj)) {
            $check = 0;
            $flash = $bobj->get_flash_file();
            $image = $bobj->get_image_file();

            if ($flash != "" && file_exists($this->config->item("banner_publish_path") . $flash)) {
                $ret["flash"] = $flash;
                $check++;
            }
            if ($image != "" && file_exists($this->config->item("banner_publish_path") . $image)) {
                $ret["image"] = $flash;
                $check++;
            }
            if ($check) {
                return array("flash" => $flash, "image" => $image, "link" => $bobj->get_link());
            } else {
                if ($obj->get_level() > 1) {
                    return $this->get_banner_for_landpage($obj->get_parent_cat_id(), $type);
                } else {
                    return array("image" => "cat_" . $type . "_default.jpg", "flash" => "", "link" => "");
                }
            }
        } else {
            if ($obj->get_level() > 1) {
                return $this->get_banner_for_landpage($obj->get_parent_cat_id(), $type);
            } else {
                return array("image" => "cat_" . $type . "_default.jpg", "flash" => "");
            }
        }
    }
}
