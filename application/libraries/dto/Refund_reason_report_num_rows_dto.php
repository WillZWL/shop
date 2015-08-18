<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Refund_reason_report_num_rows_dto extends Base_dto
{
    private $num_rows;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_num_rows()
    {
        return $this->num_rows;
    }

    public function set_num_rows($value)
    {
        $this->num_rows = $value;
    }

}

?>