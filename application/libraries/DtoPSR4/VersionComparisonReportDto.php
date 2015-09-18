<?php
class VersionComparisonReportDto
{
    private $country_name;
    private $platform_country_id;
    private $platform_id;
    private $sku;
    private $prod_name;

    public function setCountryName($country_name)
    {
        $this->country_name = $country_name;
    }

    public function getCountryName()
    {
        return $this->country_name;
    }

    public function setPlatformCountryId($platform_country_id)
    {
        $this->platform_country_id = $platform_country_id;
    }

    public function getPlatformCountryId()
    {
        return $this->platform_country_id;
    }

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

}
