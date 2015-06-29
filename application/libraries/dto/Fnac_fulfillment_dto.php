<?defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Fnac_fulfillment_dto extends Base_dto
{
    private $so_no=" ";
    private $platform_order_id=" ";
    private $prod_sku=" ";
    private $ext_item_cd=" ";
    private $qty = -1;
    private $courier_id=" ";
    private $reserve1 = " ";
    private $reserve2 = " ";
    private $reserve3 = " ";
    private $reserve4 = " ";
    private $shipdate=" ";
    private $carriercode = "OTHER";
    private $shippingmethod = "International";
    private $tracking_no=" ";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_tracking_no()
    {
        return $this->tracking_no?$this->tracking_no:" ";
    }

    public function set_tracking_no($value)
    {
        $this->tracking_no = $value;
    }

    public function get_shippingmethod()
    {
        return $this->shippingmethod;
    }

    public function set_shippingmethod($value)
    {
        $this->shippingmethod = $value;
    }

    public function get_reserve1()
    {
        return $this->reserve1;
    }

    public function set_reserve1($value)
    {
        $this->reserve1 = $value;
    }

    public function get_reserve2()
    {
        return $this->reserve2;
    }

    public function set_reserve2($value)
    {
        $this->reserve2 = $value;
    }

    public function get_reserve3()
    {
        return $this->reserve3;
    }

    public function set_reserve3($value)
    {
        $this->reserve3 = $value;
    }

    public function get_reserve4()
    {
        return $this->reserve4;
    }

    public function set_reserve4($value)
    {
        $this->reserve4 = $value;
    }

    public function get_shipdate()
    {
        return date("Y-m-d H:i:s",strtotime($this->shipdate));
    }

    public function set_shipdate($value)
    {
        $this->shipdate = $value;
    }

    public function get_courier_id()
    {
        return $this->courier_id;
    }

    public function set_courier_id($value)
    {
        $this->courier_id = $value;
    }

    public function get_carriercode()
    {
        return $this->carriercode;
    }

    public function set_carriercode($value)
    {
        $this->carriercode = $value;
    }

    public function get_qty()
    {
        return $this->qty;
    }

    public function set_qty($value)
    {
        $this->qty = $value;
    }

    public function get_ext_item_cd()
    {
        return $this->ext_item_cd;
    }

    public function set_ext_item_cd($value)
    {
        $this->ext_item_cd = $value;
    }

    public function get_prod_sku()
    {
        return $this->prod_sku;
    }

    public function set_prod_sku($value)
    {
        $this->prod_sku = $value;
    }

    public function get_platform_order_id()
    {
        return $this->platform_order_id;
    }

    public function set_platform_order_id($value)
    {
        $this->platform_order_id = $value;
    }

    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
    }
}
?>