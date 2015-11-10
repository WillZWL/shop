<?php
class BrandRegionVo extends \BaseVo
{
    private $brand_id;
    private $sales_region_id;
    private $src_region_id;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at;
    private $create_by;
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at;
    private $modify_by;

    private $primary_key = ['brand_id', 'sales_region_id', 'src_region_id'];

    public function setBrandId($brand_id)
    {
        $this->brand_id = $brand_id;
    }

    public function getBrandId()
    {
        return $this->brand_id;
    }

    public function setSalesRegionId($sales_region_id)
    {
        $this->sales_region_id = $sales_region_id;
    }

    public function getSalesRegionId()
    {
        return $this->sales_region_id;
    }

    public function setSrcRegionId($src_region_id)
    {
        $this->src_region_id = $src_region_id;
    }

    public function getSrcRegionId()
    {
        return $this->src_region_id;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }
}
