<?defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Fulfillment_report_dto extends Base_dto
{

	private $so_no;
	private $platform_id;
	private $tracking_no;
	private $courier_id;
	private $warehouse_id;
	private $create_on;
	private $dispatch_date;

	public function __construct()
	{
		parent::__construct();
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

	public function get_tracking_no()
	{
		return $this->tracking_no;
	}

	public function set_tracking_no($value)
	{
		$this->tracking_no = $value;
	}

	public function get_courier_id()
	{
		return $this->courier_id;
	}

	public function set_courier_id($value)
	{
		$this->courier_id = $value;
	}

	public function get_warehouse_id()
	{
		return $this->warehouse_id;
	}

	public function set_warehouse_id($value)
	{
		$this->warehouse_id = $value;
	}

	public function get_create_on()
	{
		return $this->create_on;
	}

	public function set_create_on($value)
	{
		$this->create_on = $value;
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

?>