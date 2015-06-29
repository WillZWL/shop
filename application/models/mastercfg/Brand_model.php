<?php
class Brand_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/brand_service');
        $this->load->library('service/region_service');
    }

    public function get_brand_list($where=array(), $option=array())
    {
        return $this->brand_service->get_brand_list_w_region($where, $option);
    }

    public function get_brand($where=array())
    {
        return $this->brand_service->get($where);
    }

    public function update_brand($obj)
    {
        return $this->brand_service->update($obj);
    }

    public function get_region_list($where=array(), $option=array())
    {
        return $this->region_service->get_list($where);
    }

    public function include_brand_vo()
    {
        return $this->brand_service->include_vo();
    }

    public function add_brand($obj)
    {
        return $this->brand_service->insert($obj);
    }

    public function get_brand_region($where=array())
    {
        return $this->brand_service->get_br_dao()->get($where);
    }

    public function get_brand_region_list($where=array())
    {
        return $this->brand_service->get_br_dao()->get_list($where);
    }

    public function del_brand_region($where)
    {
        return $this->brand_service->get_br_dao()->q_delete($where);
    }

    public function add_brand_region(Base_vo $obj){
        return $this->brand_service->get_br_dao()->insert($obj);
    }

    public function include_brand_region_vo()
    {
        return $this->brand_service->get_br_dao()->include_vo();
    }

}

/* End of file brand_model.php */
/* Location: ./system/application/models/brand_model.php */
?>