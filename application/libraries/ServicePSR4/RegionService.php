<?php
namespace ESG\Panther\Service;

class RegionService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getRegion($id = "")
    {
        if ($id != "") {
            $ret = $this->getDao('Region')->get(['id' => $id]);
        } else {
            $ret = $this->getDao('Region')->get();
        }
        return $ret;
    }

    public function updateRegion($data)
    {
        return $this->getDao('Region')->update($data);
    }

    public function add_region($data)
    {
        return $this->getDao('Region')->insert($data);
    }

    public function delete_region($id)
    {
        return $this->getDao('Region')->q_delete(["id" => "$id"]);
    }

    public function get_all_region($offset = "")
    {
        return $this->getDao('Region')->get([], ["offset" => $offset, "limit" => $this->pagination->get_num_records_per_page()]);
    }

    public function getRegionByCountryAndName($countryid, $region_name, $classname)
    {
        return $this->getDao('RegionCountry')->getRegionByCountryAndName($countryid, $region_name, $classname);
    }


    public function getCountryInRegion($value)
    {
        $rtn = [];
        $obj_array = $this->getDao('RegionCountry')->getRegionidCountryname($value, "RegionCountrynameDto");
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
        if ($obj_array = $this->getDao('Country')->getList($where, $option)) {
            foreach ($obj_array as $obj) {
                $rtn[$obj->getCountryId()] = $obj->getName();
            }
        }

        return $rtn;
    }

    public function addRegionCountry($region_id, $country)
    {
        $result = TRUE;
        $obj = $this->getDao('RegionCountry')->get();
        $obj->setRegionId($region_id);
        if ($country) {
            foreach ($country as $value) {
                $obj->setCountryId($value);
                $result = $result && $this->getDao('RegionCountry')->insert($obj);
            }
        }

        return $result;
    }

    public function delRegionCountry($region_id)
    {
        if ($objList = $this->getDao('RegionCountry')->getList(["region_id" => $region_id], ['limit' => -1])) {
            foreach ($objList as $obj) {
                $this->getDao('RegionCountry')->delete($obj);
            }
        }
        return;
    }

    public function getRegionByName($region_name = "", $type = "", $option = [])
    {
        return $this->getDao('Region')->getRegionByNameAndType($region_name, $type, $option);
    }

    public function getSellCountryList($detail = 1)
    {
        if ($detail) {
            $rs = [];
            if ($objlist = $this->getDao('Country')->getSellCountryList($detail)) {
                foreach ($objlist as $obj) {
                    $rs[$obj->getCountryId()] = $obj->getName();
                }
            }
            return $rs;
        } else {
            return $this->getDao('Country')->getSellCountryList($detail);
        }
    }

    public function getFullCountryList($detail = 1)
    {
        if ($detail) {
            $rs = [];
            if ($objlist = $this->getDao('Country')->getFullCountryList($detail)) {
                foreach ($objlist as $obj) {
                    $rs[$obj->getCountryId()] = $obj->getName();
                }
            }
            return $rs;
        } else {
            return $this->getDao('Country')->getFullCountryList($detail);
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
