<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Display_banner_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/display_banner_service');
        $this->load->library('service/language_service');
        $this->load->library('service/country_service');
        $this->load->library('service/graphic_service');
    }

    public function get_display_list($where = array(), $option = array())
    {
        return $this->display_banner_service->get_display_list($where, $option);
    }

    public function get_display($where = array())
    {
        return $this->display_banner_service->get_display($where);
    }

    public function get_dbc_num_rows($where = array())
    {
        return $this->display_banner_service->get_dbc_num_rows($where);
    }

    public function get_db_num_rows($where = array())
    {
        return $this->display_banner_service->get_db_num_rows($where);
    }

    public function get_display_banner_config($where = array())
    {
        return $this->display_banner_service->get_display_banner_config($where);
    }

    public function get_display_banner($where = array())
    {
        return $this->display_banner_service->get_display_banner($where);
    }

    public function get_display_banner_list($where = array(), $option = array())
    {
        return $this->display_banner_service->get_display_banner_list($where, $option);
    }

    public function get_db_w_graphic($banner_type, $display_id, $position_id, $slide_id, $country_id, $lang_id, $usage, $backup_image="")
    {
        return $this->display_banner_service->get_db_w_graphic($banner_type, $display_id, $position_id, $slide_id, $country_id, $lang_id, $usage, $backup_image);
    }

    public function get_display_banner_config_list($where = array(), $option = array())
    {
        return $this->display_banner_service->get_display_banner_config_list($where, $option);
    }

    public function get_language_list($where = array(), $option = array())
    {
        return $this->language_service->get_list($where, $option);
    }

    public function get_country($where = array())
    {
        return $this->country_service->get($where);
    }

    public function get_country_list($where = array(), $option = array())
    {
        return $this->country_service->get_list($where, $option);
    }

    public function graphic_seq_next_val()
    {
        return $this->graphic_service->seq_next_val();
    }

    public function update_graphic_seq($value)
    {
        return $this->graphic_service->update_seq($value);
    }

    public function insert_graphic($obj)
    {
        return $this->graphic_service->insert($obj);
    }

    public function update_graphic($obj)
    {
        return $this->graphic_service->update($obj);
    }

    public function get_graphic($where = array())
    {
        return $this->graphic_service->get($where);
    }

    public function insert_display_banner($obj)
    {
        $this->db->_protect_identifiers = TRUE;
        return $this->display_banner_service->insert($obj);
    }

    public function update_display_banner($obj)
    {
        $this->db->_protect_identifiers = TRUE;
        return $this->display_banner_service->update($obj);
    }

    public function update_display_banner_config($obj)
    {
        $this->db->_protect_identifiers = TRUE;
        return $this->display_banner_service->update_display_banner_config($obj);
    }

    public function insert_display_banner_config($obj)
    {
        $this->db->_protect_identifiers = TRUE;
        return $this->display_banner_service->insert_display_banner_config($obj);
    }

    public function get_different_country_list($display_id, $lang_id)
    {
        return $this->display_banner_service->get_different_country_list($display_id, $lang_id);
    }

    public function get_publish_banner($display_id, $position_id, $country_id, $lang_id, $usage = "PB")
    {
        return $this->display_banner_service->get_publish_banner($display_id, $position_id, $country_id, $lang_id, $usage = "PB");
    }
}
/* End of file display_banner_model.php */
/* Location: ./app/models/marketing/display_banner_model.php */
