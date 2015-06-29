<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_banner_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/context_config_service');
        $this->load->library('service/country_service');
        $this->load->library('service/language_service');
        $this->load->library('service/category_service');
        $this->load->helper(array('url', 'notice', 'image'));
        $this->load->library('upload');
    }

    public function get_country_list($where, $option)
    {
        return $this->country_service->get_list($where, $option);
    }

    public function get_language_list($where, $option)
    {
        return $this->language_service->get_list($where, $option);
    }

    public function get_country($where)
    {
        return $this->country_service->get($where);
    }

    public function get_language($where)
    {
        return $this->language_service->get($where);
    }

    public function get_sub_cat_list($where = array(), $option = array())
    {
        return $this->category_service->get_list($where, $option);
    }

    public function edit()
    {
        return;
    }

    public function get_cat_ban($where = array())
    {
        return $this->category_service->get_cat_ban($where);
    }

    public function get_cat_ban_list($lang_id)
    {
        return $this->category_service->get_cat_ban_list($lang_id);
    }

    public function insert_cat_ban($obj)
    {
        return $this->category_service->insert_cat_ban($obj);
    }

    public function update_cat_ban($obj)
    {
        return $this->category_service->update_cat_ban($obj);
    }

    public function upload_image($info)
    {

        $config['upload_path'] = $info["upload_path"];
        $config['allowed_types'] = $info["allowed_types"];
        $config['file_name'] = $info['file_name'];
        $config['overwrite'] = TRUE;
        $config['is_image'] = $info['is_image'];
        $this->upload->initialize($config);

        if ($this->upload->do_upload($info['name'])) {
            $res = $this->upload->data();
            $ext = substr($res["file_ext"], 1);

            return $ext;
        } else {
            $_SESSION["NOTICE"] = "upload_error : " . $this->upload->display_errors();
        }
    }

    public function get_category($where)
    {
        return $this->category_service->get_dao()->get($where);
    }
}


