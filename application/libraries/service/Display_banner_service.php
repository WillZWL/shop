<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Display_banner_service extends Base_service
{
    private $d_dao;
    private $dbc_dao;

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Display_banner_dao.php");
        $this->set_dao(new Display_banner_dao());
        include_once(APPPATH."libraries/dao/Display_dao.php");
        $this->set_d_dao(new Display_dao());
        include_once(APPPATH."libraries/dao/Display_banner_config_dao.php");
        $this->set_dbc_dao(new Display_banner_config_dao());
        include_once(APPPATH."libraries/service/Context_config_service.php");
        $this->context_config_service = new Context_config_service();
    }

    public function get_config_service()
    {
        return $this->config_service;
    }

    public function set_config_service($serv)
    {
        $this->config_service = $serv;
    }

    public function get_d_dao()
    {
        return $this->d_dao;
    }

    public function set_d_dao(Base_dao $dao)
    {
        $this->d_dao = $dao;
    }

    public function get_dbc_dao()
    {
        return $this->dbc_dao;
    }

    public function set_dbc_dao(Base_dao $dao)
    {
        $this->dbc_dao = $dao;
    }

    public function get_display($where)
    {
        return $this->get_d_dao()->get($where);
    }

    public function get_display_list($where, $option)
    {
        return $this->get_d_dao()->get_list($where, $option);
    }

    public function get_dbc_num_rows($where)
    {
        return $this->get_dbc_dao()->get_num_rows($where);
    }

    public function get_db_num_rows($where)
    {
        return $this->get_dao()->get_num_rows($where);
    }

    public function get_display_banner_config($where)
    {
        return $this->get_dbc_dao()->get($where);
    }

    public function get_display_banner($where)
    {
        return $this->get_dao()->get($where);
    }

    public function get_display_banner_list($where, $option)
    {
        return $this->get_dao()->get_list($where, $option);
    }

    public function get_db_w_graphic($banner_type, $display_id, $position_id, $slide_id, $country_id, $lang_id, $usage, $backup_image)
    {
        return $this->get_dao()->get_db_w_graphic($banner_type, $display_id, $position_id, $slide_id, $country_id, $lang_id, $usage, $backup_image);
    }

    public function get_display_banner_config_list($where, $option)
    {
        return $this->get_dbc_dao()->get_list($where, $option);
    }

    public function insert_display_banner_config($obj)
    {
        return $this->get_dbc_dao()->insert($obj);
    }

    public function update_display_banner_config($obj)
    {
        return $this->get_dbc_dao()->update($obj);
    }

    public function get_different_country_list($display_id, $lang_id)
    {
        return $this->get_dbc_dao()->get_different_country_list($display_id, $lang_id);
    }

    public function get_publish_banner($display_id, $position_id, $country_id, $lang_id, $usage = "PB")
    {
        define('GRAPHIC_PH', $this->context_config_service->value_of("default_graphic_path"));
        //get country banner
        $dbc = $this->get_dbc_dao()->get(array('display_id'=>$display_id, 'usage'=>$usage, 'country_id'=>$country_id, 'lang_id'=>$lang_id, 'position_id'=>$position_id, 'status'=>1));
        if(!$dbc)
        {
            //if not country banner, use country default language
            $dbc = $this->get_dbc_dao()->get(array('display_id'=>$display_id, 'usage'=>$usage, 'country_id IS NULL'=>NULL, 'lang_id'=>$lang_id, 'position_id'=>$position_id, 'status'=>1));
            if(!$dbc)
            {
                //if not country default language, use banner in english
                $dbc = $this->get_dbc_dao()->get(array('display_id'=>$display_id, 'usage'=>$usage, 'country_id IS NULL'=>NULL, 'lang_id'=>'en', 'position_id'=>$position_id, 'status'=>1));
            }
        }
        if($dbc)
        {
            $banner["publish_key"] = $dbc->get_display_id()."_".$dbc->get_position_id();
            $banner["banner_height"] = $dbc->get_height();
            $banner["banner_width"] = $dbc->get_width();
            $banner["banner_type"] = $dbc->get_banner_type();
            $dbc_id = $dbc->get_id();
            $country_id = $dbc->get_country_id();
            $lang_id = $dbc->get_lang_id();

            $banner["backup_graphic"] = $banner["backup_link_type"] = $banner["backup_link"] = $banner["time_interval"] = "";
            $redirect_link = $link_type = $graphic = array();

            if($banner["banner_type"] == "I")
            {
                $db_obj = $this->get_dao()->get_db_w_graphic($banner["banner_type"], $display_id, $position_id, 0, $country_id, $lang_id, $usage, FALSE);
                if($db_obj)
                {
                    if($db_obj->get_status() == 1 && file_exists(GRAPHIC_PH.$db_obj->get_graphic_location().$db_obj->get_graphic_file()))
                    {
                        $redirect_link[0] = $db_obj->get_link();
                        $link_type[0] = $db_obj->get_link_type();
                        $graphic[0] = "/".GRAPHIC_PH.$db_obj->get_graphic_location().$db_obj->get_graphic_file();
                        $graphic_location[0] = $db_obj->get_graphic_location();
                        $graphic_file[0] = $db_obj->get_graphic_file();
                    }
                }
            }
            elseif($banner["banner_type"] == "F")
            {
                $db_obj = $this->get_dao()->get_db_w_graphic($banner["banner_type"], $display_id, $position_id, 0, $country_id, $lang_id, $usage);
                if($db_obj->get_status() == 1 && file_exists(GRAPHIC_PH.$db_obj->get_graphic_location().$db_obj->get_graphic_file()))
                {
                    $redirect_link[0] = $db_obj->get_link();
                    $link_type[0] = $db_obj->get_link_type();

                    $file = explode('.', $db_obj->get_graphic_file());
                    $graphic[0] = "/".GRAPHIC_PH.$db_obj->get_graphic_location().$file[0];
                    $graphic_location[0] = $db_obj->get_graphic_location();
                    $graphic_file[0] = $db_obj->get_graphic_file();
                }
                $image_db_obj = $this->get_dao()->get_db_w_graphic($banner["banner_type"], $display_id, $position_id, 0, $country_id, $lang_id, $usage, TRUE);
                if($image_db_obj->get_status() == 1 && file_exists(GRAPHIC_PH.$image_db_obj->get_graphic_location().$image_db_obj->get_graphic_file()))
                {
                    $banner["backup_link"] = $image_db_obj->get_link();
                    $banner["backup_link_type"] = $image_db_obj->get_link_type();
                    $banner["backup_graphic"] = "/".GRAPHIC_PH.$image_db_obj->get_graphic_location().$image_db_obj->get_graphic_file();
                }
            }
            elseif($banner["banner_type"] == "R")
            {
                if($country_id)
                {
                    $db_list = $this->get_dao()->get_list(array('position_id'=>$position_id, 'display_id'=>$display_id, 'usage'=>$usage, 'country_id'=>$country_id, 'lang_id'=>$lang_id, 'status'=>1), array("orderby"=>"priority DESC"));
                }
                else
                {
                    $db_list = $this->get_dao()->get_list(array('position_id'=>$position_id, 'display_id'=>$display_id, 'usage'=>$usage, 'country_id IS NULL'=>NULL, 'lang_id'=>$lang_id, 'status'=>1), array("orderby"=>"priority DESC"));
                }
                if($db_list)
                {
                    $num = 0;
                    foreach($db_list AS $db_temp)
                    {
                        $db_obj = $this->get_dao()->get_db_w_graphic($banner["banner_type"], $display_id, $position_id, $db_temp->get_slide_id(), $country_id, $lang_id, $usage);
                        $redirect_link[$num] = $db_obj->get_link();
                        $link_type[$num] = $db_obj->get_link_type();
                        $time_interval = $db_obj->get_time_interval();
                        $graphic[$num] = "/".GRAPHIC_PH.$db_obj->get_graphic_location().$db_obj->get_graphic_file();
                        $graphic_location[$num] = $db_obj->get_graphic_location();
                        $graphic_file[$num] = $db_obj->get_graphic_file();
                        $num++;
                    }
                    $banner["num"] = $num;
                }
            }
            $banner["redirect_link"] = $redirect_link;
            $banner["link_type"] = $link_type;
            $banner["graphic"] = $graphic;
            $banner["graphic_location"] = $graphic_location;
            $banner["graphic_file"] = $graphic_file;
            $banner["time_interval"] = $time_interval * 1000;
        }

        return $banner;
    }
}

/* End of file Display_banner_service.php */
/* Location: ./app/libraries/service/Display_banner_service.php */