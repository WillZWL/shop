<?php
class SliProdWebfeedDto
{
    private $sku;
    private $price_g_b_p;
    private $r_r_pprice_g_b_p;
    private $price_e_u_r;
    private $r_r_pprice_e_u_r;
    private $website_quantity;
    private $website_status;
    private $bundle;

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPriceGBP($price_g_b_p)
    {
        $this->price_g_b_p = $price_g_b_p;
    }

    public function getPriceGBP()
    {
        return $this->price_g_b_p;
    }

    public function setRRPpriceGBP($r_r_pprice_g_b_p)
    {
        $this->r_r_pprice_g_b_p = $r_r_pprice_g_b_p;
    }

    public function getRRPpriceGBP()
    {
        return $this->r_r_pprice_g_b_p;
    }

    public function setPriceEUR($price_e_u_r)
    {
        $this->price_e_u_r = $price_e_u_r;
    }

    public function getPriceEUR()
    {
        return $this->price_e_u_r;
    }

    public function setRRPpriceEUR($r_r_pprice_e_u_r)
    {
        $this->r_r_pprice_e_u_r = $r_r_pprice_e_u_r;
    }

    public function getRRPpriceEUR()
    {
        return $this->r_r_pprice_e_u_r;
    }

    public function setWebsiteQuantity($website_quantity)
    {
        $this->website_quantity = $website_quantity;
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setWebsiteStatus($website_status)
    {
        $this->website_status = $website_status;
    }

    public function getWebsiteStatus()
    {
        return $this->website_status;
    }

    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
    }

    public function getBundle()
    {
        return $this->bundle;
    }

}
