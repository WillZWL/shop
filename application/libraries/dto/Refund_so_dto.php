<? defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Refund_so_dto extends Base_dto
{
    private $id;
    private $so_no;
    private $platform_order_id;
    private $platform_id;
    private $txn_id;
    private $total_refund_amount;
    private $currency_id;
    private $create_on;
    private $create_by;
    private $order_date;
    private $dispatch_date;
    private $modify_on;
    private $payment_gateway;
    private $refund_score;
    private $refund_score_date;
    private $refund_reason;
    private $special_order;
    private $pack_date;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_currency_id()
    {
        return $this->currency_id;
    }

    public function set_currency_id($value)
    {
        $this->currency_id = $value;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
    }

    public function get_order_date()
    {
        return $this->order_date;
    }

    public function set_order_date($value)
    {
        $this->order_date = $value;
    }

    public function get_dispatch_date()
    {
        return $this->dispatch_date;
    }

    public function set_dispatch_date($value)
    {
        $this->dispatch_date = $value;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
    }

    public function get_total_refund_amount()
    {
        return $this->total_refund_amount;
    }

    public function set_total_refund_amount($value)
    {
        $this->total_refund_amount = $value;
    }

    public function get_platform_id()
    {
        return $this->platform_id;
    }

    public function set_platform_id($value)
    {
        $this->platform_id = $value;
    }

    public function get_txn_id()
    {
        return $this->txn_id;
    }

    public function set_txn_id($value)
    {
        $this->txn_id = $value;
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

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($value)
    {
        $this->id = $value;
    }

    public function get_payment_gateway()
    {
        return $this->payment_gateway;
    }

    public function set_payment_gateway($value)
    {
        $this->payment_gateway = $value;
    }

    public function get_refund_score()
    {
        return $this->refund_score;
    }

    public function set_refund_score($value)
    {
        $this->refund_score = $value;
    }

    public function get_refund_score_date()
    {
        return $this->refund_score_date;
    }

    public function set_refund_score_date($value)
    {
        $this->refund_score_date = $value;
    }

    public function get_refund_reason()
    {
        return $this->refund_reason;
    }

    public function set_refund_reason($value)
    {
        $this->refund_reason = $value;
    }

    public function get_special_order()
    {
        return $this->special_order;
    }

    public function set_special_order($value)
    {
        $this->special_order = $value;
    }

    public function get_pack_date()
    {
        return $this->pack_date;
    }

    public function set_pack_date($value)
    {
        $this->pack_date = $value;
    }
}

?>