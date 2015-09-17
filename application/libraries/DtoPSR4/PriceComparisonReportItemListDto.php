<?php
class PriceComparisonReportItemListDto
{
    private $sku;
    private $name;
    private $country;
    private $website_platform;
    private $website_price;
    private $skype_platform;
    private $skype_price;

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setWebsitePlatform($website_platform)
    {
        $this->website_platform = $website_platform;
    }

    public function getWebsitePlatform()
    {
        return $this->website_platform;
    }

    public function setWebsitePrice($website_price)
    {
        $this->website_price = $website_price;
    }

    public function getWebsitePrice()
    {
        return $this->website_price;
    }

    public function setSkypePlatform($skype_platform)
    {
        $this->skype_platform = $skype_platform;
    }

    public function getSkypePlatform()
    {
        return $this->skype_platform;
    }

    public function setSkypePrice($skype_price)
    {
        $this->skype_price = $skype_price;
    }

    public function getSkypePrice()
    {
        return $this->skype_price;
    }

}
