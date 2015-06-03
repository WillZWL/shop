<?defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Feedback_email_dto extends Base_dto
{
	private $so_no;
	private $biz_type;
	private $delivery_country_id;
	private $warehouse_id;
	private $courier_id;
	private $forename;
	private $email;
	private $conv_site_id;
	private $platform_id;

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

	public function get_biz_type()
	{
		return $this->biz_type;
	}

	public function set_biz_type($value)
	{
		$this->biz_type = $value;
	}

	public function get_delivery_country_id()
	{
		return $this->delivery_country_id;
	}

	public function set_delivery_country_id($value)
	{
		$this->delivery_country_id = $value;
	}

	public function get_warehouse_id()
	{
		return $this->warehouse_id;
	}

	public function set_warehouse_id($value)
	{
		$this->warehouse_id = $value;
	}

	public function get_courier_id()
	{
		return $this->courier_id;
	}

	public function set_courier_id($value)
	{
		$this->courier_id = $value;
	}

	public function get_forename()
	{
		return $this->forename;
	}

	public function set_forename($value)
	{
		$this->forename = $value;
	}

	public function get_email()
	{
		return $this->email;
	}

	public function set_email($value)
	{
		$this->email = $value;
	}

	public function get_conv_site_id()
	{
		return $this->conv_site_id;
	}

	public function set_conv_site_id($value)
	{
		$this->conv_site_id = $value;
	}

	public function get_platform_id()
	{
		return $this->platform_id;
	}

	public function set_platform_id($value)
	{
		$this->platform_id = $value;
	}

}

?>