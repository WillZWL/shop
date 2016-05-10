<?php
class ProductIdentifierVo extends \BaseVo
{
    private $id;
    private $prod_grp_cd;
    private $colour_id;
    private $country_id;
    private $ean;
    private $mpn;
    private $upc;
    private $status = '0';


    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setProdGrpCd($prod_grp_cd)
    {
        $this->prod_grp_cd = $prod_grp_cd;
    }

    public function getProdGrpCd()
    {
        return $this->prod_grp_cd;
    }

    public function setColourId($colour_id)
    {
        $this->colour_id = $colour_id;
    }

    public function getColourId()
    {
        return $this->colour_id;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    public function getEan()
    {
        return $this->ean;
    }

    public function setMpn($mpn)
    {
        $this->mpn = $mpn;
    }

    public function getMpn()
    {
        return $this->mpn;
    }

    public function setUpc($upc)
    {
        $this->upc = $upc;
    }

    public function getUpc()
    {
        return $this->upc;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }



}
