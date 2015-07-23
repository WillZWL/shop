<? defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Order_release_activity_log_dto extends Base_dto
{
    private $order_number;
    private $hold_reason;
    private $hold_date;
    private $hold_by;
    private $release_reason;
    private $release_date;
    private $release_at;
    private $release_by;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_order_number()
    {
        return $this->order_number;
    }

    public function set_order_number($value)
    {
        $this->order_number = $value;
    }

    public function get_hold_reason()
    {
        return $this->hold_reason;
    }

    public function set_hold_reason($value)
    {
        $this->hold_reason = $value;
    }

    public function get_hold_date()
    {
        return $this->hold_date;
    }

    public function set_hold_date($value)
    {
        $this->hold_date = $value;
    }

    public function get_hold_by()
    {
        return $this->hold_by;
    }

    public function set_hold_by($value)
    {
        $this->hold_by = $value;
    }

    public function get_release_reason()
    {
        return $this->release_reason;
    }

    public function set_release_reason($value)
    {
        $this->release_reason = $value;
    }

    public function get_release_date()
    {
        return $this->release_date;
    }

    public function set_release_date($value)
    {
        $this->release_date = $value;
    }

    public function get_release_at()
    {
        return $this->release_at;
    }

    public function set_release_at($value)
    {
        $this->release_at = $value;
    }

    public function get_release_by()
    {
        return $this->release_by;
    }

    public function set_release_by($value)
    {
        $this->release_by = $value;
    }
}

?>