<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "import_info_dto.php";

class Import_finance_dispatch_dto extends Import_info_dto
{
    protected $so_no;
    protected $finance_dispatch_date;

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_finance_dispatch_date()
    {
        return $this->finance_dispatch_date;
    }

    public function set_finance_dispatch_date($value)
    {
        $this->finance_dispatch_date = $value;
    }
}
