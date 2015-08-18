<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Compensation_report_dto extends Base_dto
{
    private $platform_id;
    private $so_no;
    private $prod_name;
    private $item_sku;
    private $request_by;
    private $request_date;
    private $approval_date;
    private $approved_by;
    private $reason;

    public function __construct()
    {
        parent::__construct();
    }


    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_item_sku()
    {
        return $this->item_sku;
    }

    public function set_item_sku($value)
    {
        $this->item_sku = $value;
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