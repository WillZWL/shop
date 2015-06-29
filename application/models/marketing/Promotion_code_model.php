<?php
class Promotion_code_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/promotion_code_service');
        $this->load->library('service/product_service');
        $this->load->library('service/brand_service');
        $this->load->library('service/category_service');
        $this->load->library('service/courier_service');
    }

    public function get_list($service, $where=array(), $option=array())
    {
        $service = $service."_service";
        return $this->$service->get_list($where, $option);
    }

    public function get($service, $where=array())
    {
        $service = $service."_service";
        return $this->$service->get_dao()->get($where);
    }

    public function update($service, $obj)
    {
        $service = $service."_service";
        return $this->$service->update($obj);
    }

    public function include_dto($service, $dto)
    {
        $service = $service."_service";
        return $this->$service->include_dto($dto);
    }

    public function add($service, $obj)
    {
        $service = $service."_service";
        return $this->$service->insert($obj);
    }

    public function get_delivery_option_list()
    {
        return $this->courier_service->get_list(array('weight_type'=>'CH'));
    }
}

/* End of file promotion_code_model.php */
/* Location: ./system/application/models/promotion_code_model.php */
