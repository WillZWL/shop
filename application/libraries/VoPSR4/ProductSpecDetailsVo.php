<?php
class ProductSpecDetailsVo extends \BaseVo
{
    private $id;
    private $ps_id;
    private $cat_id;
    private $prod_sku;
    private $lang_id;
    private $cps_unit_id;
    private $text;
    private $start_value = '0.0000';
    private $start_standardize_value = '0.0000';
    private $end_value = '0.0000';
    private $end_standardize_value = '0.0000';
    private $status = '1';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPsId($ps_id)
    {
        $this->ps_id = $ps_id;
    }

    public function getPsId()
    {
        return $this->ps_id;
    }

    public function setCatId($cat_id)
    {
        $this->cat_id = $cat_id;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setLangId($lang_id)
    {
        $this->lang_id = $lang_id;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setCpsUnitId($cps_unit_id)
    {
        $this->cps_unit_id = $cps_unit_id;
    }

    public function getCpsUnitId()
    {
        return $this->cps_unit_id;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setStartValue($start_value)
    {
        $this->start_value = $start_value;
    }

    public function getStartValue()
    {
        return $this->start_value;
    }

    public function setStartStandardizeValue($start_standardize_value)
    {
        $this->start_standardize_value = $start_standardize_value;
    }

    public function getStartStandardizeValue()
    {
        return $this->start_standardize_value;
    }

    public function setEndValue($end_value)
    {
        $this->end_value = $end_value;
    }

    public function getEndValue()
    {
        return $this->end_value;
    }

    public function setEndStandardizeValue($end_standardize_value)
    {
        $this->end_standardize_value = $end_standardize_value;
    }

    public function getEndStandardizeValue()
    {
        return $this->end_standardize_value;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
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

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
