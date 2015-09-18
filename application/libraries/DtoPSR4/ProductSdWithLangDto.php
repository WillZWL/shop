<?php
class ProductSdWithLangDto
{
    private $psg_name;
    private $ps_id;
    private $cat_id;
    private $ps_name;
    private $unit_id;
    private $prod_sku;
    private $text;
    private $start_value;
    private $start_standardize_value;
    private $end_value;
    private $end_standardize_value;

    public function setPsgName($psg_name)
    {
        $this->psg_name = $psg_name;
    }

    public function getPsgName()
    {
        return $this->psg_name;
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

    public function setPsName($ps_name)
    {
        $this->ps_name = $ps_name;
    }

    public function getPsName()
    {
        return $this->ps_name;
    }

    public function setUnitId($unit_id)
    {
        $this->unit_id = $unit_id;
    }

    public function getUnitId()
    {
        return $this->unit_id;
    }

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
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

}
