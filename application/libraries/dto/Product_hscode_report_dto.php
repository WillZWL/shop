<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Product_hscode_report_dto extends Base_dto
{
    private $mastersku;
    private $sku;
    private $product;
    private $category;
    private $subcategory;
    private $sub_subcategory;
    private $country_id;
    private $hscode;
    private $hsdescription;
    private $duty_pcent;

    public function __construct()
    {
        parent::__construct();
    }


    public function get_mastersku()
    {
        return $this->mastersku;
    }

    public function set_mastersku($value)
    {
        $this->mastersku = $value;
    }

    public function get_sku()
    {
        return $this->sku;
    }

    public function set_sku($value)
    {
        $this->sku = $value;
    }

    public function get_product()
    {
        return $this->product;
    }

    public function set_product($value)
    {
        $this->product = $value;
    }

    public function get_category()
    {
        return $this->category;
    }

    public function set_category($value)
    {
        $this->category = $value;
    }

    public function get_request_by()
    {
        return $this->request_by;
    }

    public function set_request_by($value)
    {
        $this->request_by = $value;
    }

    public function get_approval_date()
    {
        return $this->approval_date;
    }

    public function set_approval_date($value)
    {
        $this->approval_date = $value;
    }

    public function get_request_date()
    {
        return $this->request_date;
    }

    public function set_request_date($value)
    {
        $this->request_date = $value;
    }

    public function get_approved_by()
    {
        return $this->approved_by;
    }

    public function set_approved_by($value)
    {
        $this->approved_by = $value;
    }

    public function get_reason()
    {
        return $this->reason;
    }

    public function set_reason($value)
    {
        $this->reason = $value;
    }
}

?>