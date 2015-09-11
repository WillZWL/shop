<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\RegionDao;
use ESG\Panther\Dao\CountryDao;
use ESG\Panther\Dao\RegionCountryDao;

class RegionService extends BaseService
{
    public function __construct()
    {
        $CI =& get_instance();
        $CI->load->library('service/pagination_service');
        $this->setDao(new RegionDao);
        $this->setCountryDao(new CountryDao);
        $this->setRegionCountryDao(new RegionCountryDao);
        $this->pagination_service = $CI->pagination_service;
    }

    public function getCountryDao()
    {
        return $this->countryDao;
    }

    public function setCountryDao($dao)
    {
        $this->countryDao = $dao;
    }

    public function getRegionCountryDao()
    {
        return $this->regionCountryDao;
    }

    public function setRegionCountryDao($dao)
    {
        $this->regionCountryDao = $dao;
    }

    public function getRegion($id = "")
    {
        if ($id != "") {
            $ret = $this->getDao()->get(['id' => $id]);
        } else {
            $ret = $this->getDao()->get();
        }
        return $ret;
    }

    public function updateRegion($data)
    {
        return $this->getDao()->update($data);
    }

    public function add_region($data)
    {
        return $this->getDao()->insert($data);
    }

    public function delete_region($id)
    {
        return $this->getDao()->q_delete(["id" => "$id"]);
    }

    public function get_all_region($offset = "")
    {
        return $this->getDao()->get([], ["offset" => $offset, "limit" => $this->pagination->get_num_records_per_page()]);
    }

    public function getRegionByCountryAndName($countryid, $region_name, $classname)
    {
        return $this->getRegionCountryDao()->getRegionByCountryAndName($countryid, $region_name, $classname);
    }


    public function getCountryInRegion($value)
    {
        $rtn = [];
        $obj_array = $this->getRegionCountryDao()->getRegionidCountryname($value, "RegionCountrynameDto");
        foreach ($obj_array as $obj) {
            $rtn[$obj->getCountryId()] = $obj->getName();
        }
        return $rtn;
    }

    public function getCountryEx($full_list, $input)
    {
        $rtn = $full_list;
        foreach ($input as $key => $value) {
            unset($rtn[$key]);
        }

        return $rtn;
    }

    public function getCountryList($where = [], $option = [])
    {
        $rtn = [];
        $option["limit"] = -1;
        if ($obj_array = $this->getCountryDao()->getList($where, $option)) {
            foreach ($obj_array as $obj) {
                $rtn[$obj->getId()] = $obj->getName();
            }
        }

        return $rtn;
    }

    public function addRegionCountry($region_id, $country)
    {
        $result = TRUE;
        $obj = $this->getRegionCountryDao()->get();
        $obj->setRegionId($region_id);
        if ($country) {
            foreach ($country as $value) {
                $obj->setCountryId($value);
                $result = $result && $this->getRegionCountryDao()->insert($obj);
            }
        }

        return $result;
    }

    public function delRegionCountry($region_id)
    {
        if ($objList = $this->getRegionCountryDao()->getList(["region_id" => $region_id], ['limit' => -1])) {
            foreach ($objList as $obj) {
                $this->getRegionCountryDao()->delete($obj);
            }
        }
        return;
    }

    public function getRegionByName($region_name = "", $type = "", $option = [])
    {
        return $this->getDao()->getRegionByNameAndType($region_name, $type, $option);
    }

    public function getSellCountryList($detail = 1)
    {
        if ($detail) {
            $rs = [];
            if ($objlist = $this->getCountryDao()->getSellCountryList($detail)) {
                foreach ($objlist as $obj) {
                    $rs[$obj->getId()] = $obj->getName();
                }
            }
            return $rs;
        } else {
            return $this->getCountryDao()->getSellCountryList($detail);
        }
    }

    public function getFullCountryList($detail = 1)
    {
        if ($detail) {
            $rs = [];
            if ($objlist = $this->getCountryDao()->getFullCountryList($detail)) {
                foreach ($objlist as $obj) {
                    $rs[$obj->getId()] = $obj->getName();
                }
            }
            return $rs;
        } else {
            return $this->getCountryDao()->getFullCountryList($detail);
        }
    }

    public function getRegNameListWKey($where = [], $option = [])
    {
        $data = [];
        if ($obj_list = $this->getList($where, $option)) {
            foreach ($obj_list as $obj) {
                $data[$obj->getId()] = $obj->getRegionName();
            }
        }
        return $data;
    }

}

?>
