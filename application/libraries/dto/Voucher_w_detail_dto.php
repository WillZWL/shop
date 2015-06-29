<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Voucher_w_detail_dto extends Base_dto
{

    private $id;
    private $voucher_id;
    private $type;
    private $party;
    private $expire_date;
    private $code;
    private $distributed;
    private $total_distribution;
    private $status;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
    }

    public function get_voucher_id()
    {
        return $this->voucher_id;
    }

    public function set_voucher_id($value)
    {
        $this->voucher_id = $value;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_type($value)
    {
        $this->type = $value;
    }

    public function get_party()
    {
        return $this->party;
    }

    public function set_party($value)
    {
        $this->party = $value;
    }

    public function get_expire_date()
    {
        return $this->expire_date;
    }

    public function set_expire_date($value)
    {
        $this->expire_date = $value;
    }

    public function get_code()
    {
        return $this->code;
    }

    public function set_code($value)
    {
        $this->code = $value;
    }

    public function get_distributed()
    {
        return $this->distributed;
    }

    public function set_distributed($value)
    {
        $this->distributed = $value;
    }

    public function get_total_distribution()
    {
        return $this->total_distribution;
    }

    public function set_total_distribution($value)
    {
        $this->total_distribution = $value;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($value)
    {
        $this->status = $value;
    }
}


