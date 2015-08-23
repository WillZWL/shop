<?php
namespace AtomV2\Models\Mastercfg;

use AtomV2\Service\RegionService;

class RegionModel extends \CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->regionService = new RegionService;
    }

    public function get_region($id = "")
    {
        return $this->regionService->get_region($id);
    }

    public function update_region($data)
    {
        return $this->regionService->update_region($data);
    }

    public function add_region($data)
    {
        return $this->regionService->add_region($data);
    }

    public function delete_region($id)
    {
        return $this->regionService->delete_region($id);
    }

    public function get_all_region($offset = "")
    {
        return $this->regionService->get_all_region($offset);
    }

    public function getRegionByName($region_name, $type, $id, $option)
    {
        return $this->regionService->getRegionByName($region_name, $type, $id, $option);
    }

    public function get_country_in_region($value)
    {
        return $this->regionService->get_country_in_region($value);
    }

    public function get_country_ex($full_list, $input)
    {
        return $this->regionService->get_country_ex($full_list, $input);
    }

    public function get_country_list($where = array(), $option = array())
    {
        return $this->regionService->get_country_list($where, $option);
    }

    public function add_region_country($region_id, $country)
    {
        return $this->regionService->add_region_country($region_id, $country);
    }

    public function del_region_country($region_id)
    {
        return $this->regionService->del_region_country($region_id);
    }
}
