<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Refund_reason_report_dto extends Base_dto
{
    private $rank;
    private $reason;
    private $percentage;
    private $products;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_rank()
    {
        return $this->rank;
    }

    public function set_rank($value)
    {
        $this->rank = $value;
    }

    public function get_reason()
    {
        return $this->reason;
    }

    public function set_reason($value)
    {
        $this->reason = $value;
    }

    public function get_percentage()
    {
        return $this->percentage;
    }

    public function set_percentage($value)
    {
        $this->percentage = $value;
    }

    public function get_products()
    {
        return $this->products;
    }

    public function set_products($value)
    {
        $this->products = $value;
    }
}

?>