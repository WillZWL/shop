<?php
class ProductGoogleGscCommentDto
{
    private $sku;
    private $prod_grp_cd;
    private $colour_id;
    private $lang_id;
    private $country_id;
    private $prod_status;

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($value)
    {
        $this->sku = $value;
    }

    public function getProdGrpCd()
    {
        return $this->prod_grp_cd;
    }

    public function setProdGrpCd($value)
    {
        $this->prod_grp_cd = $value;
    }

    public function getColourId()
    {
        return $this->colour_id;
    }

    public function setColourId($value)
    {
        $this->colour_id = $value;
    }

    public function getLangId()
    {
        return $this->lang_id;
    }

    public function setLangId($value)
    {
        $this->lang_id = $value;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setCountryId($value)
    {
        $this->country_id = $value;
    }

    public function getProdStatus()
    {
        return $this->prod_status;
    }

    public function setProdStatus($value)
    {
        $this->prod_status = $value;
    }

}


