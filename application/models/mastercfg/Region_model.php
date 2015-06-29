<?php
class Region_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/region_service');
    }

    public function get_region($id="")
    {
        return $this->region_service->get_region($id);
    }

    public function update_region($data)
    {
        return $this->region_service->update_region($data);
    }

    public function add_region($data)
    {
        return $this->region_service->add_region($data);
    }

    public function delete_region($id)
    {
        return $this->region_service->delete_region($id);
    }

    public function get_all_region($offset="")
    {
        return $this->region_service->get_all_region($offset);
    }

    public function get_region_by_name($region_name,$type,$id,$option)
    {
        return $this->region_service->get_region_by_name($region_name,$type,$id,$option);
    }

    public function get_country_in_region($value)
    {
        return $this->region_service->get_country_in_region($value);
    }

    public function get_country_ex($full_list,$input)
    {
        return $this->region_service->get_country_ex($full_list,$input);
    }

    public function get_country_list($where=array(), $option=array())
    {
        return $this->region_service->get_country_list($where, $option);
    }

    public function add_region_country($region_id,$country)
    {
        return $this->region_service->add_region_country($region_id, $country);
    }

    public function del_region_country($region_id)
    {
        return $this->region_service->del_region_country($region_id);
    }
}

/* End of file email.php */
/* Location: ./system/application/models/region.php */
?>