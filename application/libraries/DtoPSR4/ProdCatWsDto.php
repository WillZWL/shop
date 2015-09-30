<?php
class ProdCatWsDto
{
    private $sku;
    private $cat_id;
    private $cat_name;
    private $scat_id;
    private $scat_name;
    private $sscat_id;
    private $sscat_name;

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setCatId($cat_id)
    {
        $this->cat_id = $cat_id;
    }

    public function getCatId()
    {
        return $this->cat_id;
    }

    public function setCatName($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setScatId($scat_id)
    {
        $this->scat_id = $scat_id;
    }

    public function getScatId()
    {
        return $this->scat_id;
    }

    public function setScatName($scat_name)
    {
        $this->scat_name = $scat_name;
    }

    public function getScatName()
    {
        return $this->scat_name;
    }

    public function setSscatId($sscat_id)
    {
        $this->sscat_id = $sscat_id;
    }

    public function getSscatId()
    {
        return $this->sscat_id;
    }

    public function setSscatName($sscat_name)
    {
        $this->sscat_name = $sscat_name;
    }

    public function getSscatName()
    {
        return $this->sscat_name;
    }

}
