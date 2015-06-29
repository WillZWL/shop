<?php
include_once "Base_dto.php";

class version_comparison_report_dto extends Base_dto
{
    private $country_name;
    private $platform_country_id;
    private $platform_id;
    private $sku;
    private $prod_name;

    public function get_country_name()
    {
        return $this->country_name;
    }

    public function set_country_name($value)
    {
        $this->country_name = $value;
    }

    public function get_platform_country_id()
    {
        return $this->platform_country_id;
    }

    public function set_platform_country_id($value)
    {
        $this->platform_country_id = $value;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }
}