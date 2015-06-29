<?php
class Product_overview_amfr_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/product_service');
        $this->load->library('service/amfr_price_service');
    }

    public function get_product_list($where=array(), $option=array(), $lang=array())
    {
        return $this->amfr_price_service->get_product_overview_tr($where, $option, "Product_cost_dto", $lang);
    }

    public function get_product_list_total($where=array(),$option=array())
    {
        return $this->amfr_price_service->get_product_overview($where, array_merge($option,array("num_rows"=>1)));
    }

    public function get_list($service, $where=array(), $option=array())
    {
        $service = $service."_service";
        return $this->$service->get_list($where, $option);
    }

    public function get($service, $where=array())
    {
        $service = $service."_service";
        return $this->$service->get($where);
    }

    public function get_price($service, $where=array())
    {
        $service = $service."_service";
        return $this->$service->price_service->get($where);
    }

    public function update($service, $obj)
    {
        $service = $service."_service";
        return $this->$service->update($obj);
    }

    public function update_price($service, $obj)
    {
        $service = $service."_service";
        return $this->$service->price_service->update($obj);
    }

    public function include_vo($service)
    {
        $service = $service."_service";
        return $this->$service->include_vo();
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

    public function add_price($service, $obj)
    {
        $service = $service."_service";
        return $this->$service->price_service->insert($obj);
    }
}

/* End of file brand_model.php */
/* Location: ./system/application/models/brand_model.php */
?>