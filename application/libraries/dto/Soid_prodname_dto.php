<?defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Soid_prodname_dto extends Base_dto
{
	private $item_sku;
	private $line_no;
	private $so_no;
	private $qty;
	private $unit_price;
	private $discount;
	private $amount;
	private $status;
	private $name;
	private $image;
	private $sh_no;
	private $tracking_no;
	private $dispatch_date;
	private $gst_total;
	private $profit;
	private $margin;
	private $profit_raw;
	private $margin_raw;

	public function Soid_prodname_dto()
	{
		parent::__construct();
	}

	public function get_gst_total()
	{
		return $this->gst_total;
	}

	public function set_gst_total($value)
	{
		$this->gst_total = $value;
	}

	public function get_profit()
	{
		return $this->profit;
	}

	public function set_profit($value)
	{
		$this->profit = $value;
	}

	public function get_profit_raw()
	{
		return $this->profit_raw;
	}

	public function set_profit_raw($value)
	{
		$this->profit_raw = $value;
	}

	public function get_margin()
	{
		return $this->margin;
	}

	public function set_margin($value)
	{
		$this->margin = $value;
	}

	public function get_margin_raw()
	{
		return $this->margin_raw;
	}

	public function set_margin_raw($value)
	{
		$this->margin_raw = $value;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($value)
	{
		$this->name = $value;
	}

	public function get_image()
	{
		return $this->image;
	}

	public function set_image($value)
	{
		$this->image = $value;
	}

	public function get_status()
	{
		return $this->status;
	}

	public function set_status($value)
	{
		$this->status = $value;
	}

	public function get_amount()
	{
		return $this->amount;
	}

	public function set_amount($value)
	{
		$this->amount = $value;
	}

	public function get_discount()
	{
		return $this->discount;
	}

	public function set_discount($value)
	{
		$this->discount = $value;
	}

	public function get_unit_price()
	{
		return $this->unit_price;
	}

	public function set_unit_price($value)
	{
		$this->unit_price = $value;
	}

	public function get_qty()
	{
		return $this->qty;
	}

	public function set_qty($value)
	{
		$this->qty = $value;
	}

	public function get_so_no()
	{
		return $this->so_no;
	}

	public function set_so_no($value)
	{
		$this->so_no = $value;
	}

	public function get_line_no()
	{
		return $this->line_no;
	}

	public function set_line_no($value)
	{
		$this->line_no = $value;
	}

	public function get_item_sku()
	{
		return $this->item_sku;
	}

	public function set_item_sku($value)
	{
		$this->item_sku = $value;
	}

	public function get_sh_no()
	{
		return $this->sh_no;
	}

	public function set_sh_no($value)
	{
		$this->sh_no = $value;
	}

	public function get_tracking_no()
	{
		return $this->tracking_no;
	}

	public function set_tracking_no($value)
	{
		$this->tracking_no = $value;
	}

	public function get_dispatch_date()
	{
		return $this->dispatch_date;
	}

	public function set_dispatch_date($value)
	{
		$this->dispatch_date = $value;
	}

}

