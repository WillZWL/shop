<?defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Amazon_ack_dto extends Base_dto
{
    private $platform_order_id;
    private $so_no;
    private $status_code;
    private $ext_item_cd;
    private $prod_sku;

    public function __construct()
    {
        parent::__construct();
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

    public function get_status_code()
    {
        return $this->status_code;
    }

    public function set_status_code($value)
    {
        $this->status_code = $value;
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
}

?>