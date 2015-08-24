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

    public function getRegion($id = "")
    {
        return $this->regionService->getRegion($id);
    }

    public function updateRegion($data)
    {
        return $this->regionService->updateRegion($data);
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

    public function getCountryInRegion($value)
    {
        return $this->regionService->getCountryInRegion($value);
    }

    public function getCountryEx($full_list, $input)
    {
        return $this->regionService->getCountryEx($full_list, $input);
    }

    public function getCountryList($where = [], $option = [])
    {
        return $this->regionService->getCountryList($where, $option);
    }

    public function addRegionCountry($region_id, $country)
    {
        return $this->regionService->addRegionCountry($region_id, $country);
    }

    public function delRegionCountry($region_id)
    {
        return $this->regionService->delRegionCountry($region_id);
    }
}
