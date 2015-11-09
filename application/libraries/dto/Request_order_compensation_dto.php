<?php  defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Request_order_compensation_dto extends Base_dto
{
    private $compensation_id;
    private $so_no;
    private $platform_id;
    private $item_sku;
    private $prod_name;
    private $note;
    private $request_on;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_compensation_id()
    {
        return $this->compensation_id;
    }

    public function set_compensation_id($value)
    {
        $this->compensation_id = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_item_sku()
    {
        return $this->item_sku;
    }

    public function set_item_sku($value)
    {
        $this->item_sku = $value;
    }

    public function get_prod_name()
    {
        return $this->prod_name;
    }

    public function set_prod_name($value)
    {
        $this->prod_name = $value;
    }

    public function get_note()
    {
        return $this->note;
    }

    public function set_note($value)
    {
        $this->note = $value;
    }

    public function get_request_on()
    {
        return $this->request_on;
    }

    public function set_request_on($value)
    {
        $this->request_on = $value;
    }

}

?>