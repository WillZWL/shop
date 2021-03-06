<?php
class ProductSpecDetailsVo extends \BaseVo
{
    private $id;
    private $ps_id = '';
    private $cat_id;
    private $prod_sku;
    private $lang_id;
    private $cps_unit_id;
    private $text = '';
    private $start_value = '0.0000';
    private $start_standardize_value = '0.0000';
    private $end_value = '0.0000';
    private $end_standardize_value = '0.0000';
    private $status = '1';


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

    public function setPsId($ps_id)
    {
        if ($ps_id !== null) {
            $this->ps_id = $ps_id;
        }
    }

    public function getPsId()
    {
        return $this->ps_id;
    }

    public function setCatId($cat_id)
    {
        if ($cat_id !== null) {
            $this->cat_id = $cat_id;
        }
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setProdSku($prod_sku)
    {
        if ($prod_sku !== null) {
            $this->prod_sku = $prod_sku;
        }
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setLangId($lang_id)
    {
        if ($lang_id !== null) {
            $this->lang_id = $lang_id;
        }
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setCpsUnitId($cps_unit_id)
    {
        if ($cps_unit_id !== null) {
            $this->cps_unit_id = $cps_unit_id;
        }
    }

    public function getCpsUnitId()
    {
        return $this->cps_unit_id;
    }

    public function setText($text)
    {
        if ($text !== null) {
            $this->text = $text;
        }
    }

    public function getText()
    {
        return $this->text;
    }

    public function setStartValue($start_value)
    {
        if ($start_value !== null) {
            $this->start_value = $start_value;
        }
    }

    public function getStartValue()
    {
        return $this->start_value;
    }

    public function setStartStandardizeValue($start_standardize_value)
    {
        if ($start_standardize_value !== null) {
            $this->start_standardize_value = $start_standardize_value;
        }
    }

    public function getStartStandardizeValue()
    {
        return $this->start_standardize_value;
    }

    public function setEndValue($end_value)
    {
        if ($end_value !== null) {
            $this->end_value = $end_value;
        }
    }

    public function getEndValue()
    {
        return $this->end_value;
    }

    public function setEndStandardizeValue($end_standardize_value)
    {
        if ($end_standardize_value !== null) {
            $this->end_standardize_value = $end_standardize_value;
        }
    }

    public function getEndStandardizeValue()
    {
        return $this->end_standardize_value;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
