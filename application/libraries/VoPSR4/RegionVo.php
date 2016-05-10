<?php
class RegionVo extends \BaseVo
{
    private $id;
    private $region_name;
    private $type = 'S';


    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRegionName($region_name)
    {
        if ($region_name !== null) {
            $this->region_name = $region_name;
        }
    }

    public function getRegionName()
    {
        return $this->region_name;
    }

    public function setType($type)
    {
        if ($type !== null) {
            $this->type = $type;
        }
    }

    public function getType()
    {
        return $this->type;
    }

}
