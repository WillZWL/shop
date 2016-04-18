<?php

class WebsiteStatusAlertDto
{
    protected $sku;
    protected $master_sku;
    protected $name;
    protected $surplus_qty;
    protected $sourcing_status;
    protected $origin_website_status;
    protected $website_status;

    public function getSku()
    {
        return $this->sku;
    }

    public function setSku($value)
    {
        $this->sku = $value;
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setMasterSku($value)
    {
        $this->master_sku = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($value)
    {
        $this->name = $value;
    }

    public function getSurplusQty()
    {
        return $this->surplus_qty;
    }

    public function setSurplusQty($value)
    {
        $this->surplus_qty = $value;
    }

    public function getSourcingStatus()
    {
        return $this->sourcing_status;
    }

    public function setSourcingStatus($value)
    {
        $this->sourcing_status = $value;
    }

    public function getOriginWebsiteStatus()
    {
        return $this->origin_website_status;
    }

    public function setOriginWebsiteStatus($value)
    {
        $this->origin_website_status = $value;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setWebsiteStatus($value)
    {
        $this->website_status = $value;
    }
}