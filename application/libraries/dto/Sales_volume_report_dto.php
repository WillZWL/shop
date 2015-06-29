<?defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Sales_volume_report_dto extends Base_dto
{

    private $platform_id;
    private $prod_sku;
    private $ext_sku;
    private $name;
    private $qty;
    private $create_on;
    private $cat_name;
    private $sub_cat_name;
    private $sub_cat_cat_name;
    private $sku_create_on;

/*
 si.so_no orderNo,
 so.platform_id Platform,
 so.create_on orderCreateDate,
 si.prod_sku VBSKU,
 si.qty Qty,
 sm.ext_sku masterSKU,
 pr.`name` productName */

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

    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_prod_sku($value)
    {
        $this->prod_sku = $value;
    }

    public function get_ext_sku()
    {
        return $this->ext_sku;
    }

    public function set_ext_sku($value)
    {
        $this->ext_sku = $value;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($value)
    {
        $this->name = $value;
    }

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
    }

    public function get_cat_name()
    {
        return $this->cat_name;
    }

    public function set_cat_name($value)
    {
        $this->cat_name = $value;
    }
    public function get_sub_cat_name()
    {
        return $this->sub_cat_name;
    }

    public function set_sub_cat_name($value)
    {
        $this->sub_cat_name= $value;
    }
    public function get_sub_sub_cat_name()
    {
        return $this->sub_sub_cat_name;
    }

    public function set_sub_sub_cat_name($value)
    {
        $this->sub_sub_cat_name = $value;
    }

    public function get_sku_create_on()
    {
        return $this->sku_create_on;
    }

    public function set_sku_create_on($value)
    {
        $this->sku_create_on = $value;
    }
}

?>