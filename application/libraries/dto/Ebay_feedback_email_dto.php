<?defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_dto.php";

class Ebay_feedback_email_dto extends Base_dto
{
	private $so_no;
	private $platform_id;
	private $email;
	private $delivery_name;
	private $item_list;
	private $language_id;

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

	public function get_email()
	{
		return $this->email;
	}

	public function set_email($value)
	{
		$this->email = $value;
	}

	public function get_delivery_name()
	{
		return $this->delivery_name;
	}

	public function set_delivery_name($value)
	{
		$this->delivery_name = $value;
	}

	public function get_item_list()
	{
		return $this->item_list;
	}

	public function set_item_list($value)
	{
		$this->item_list = $value;
	}

	public function get_language_id()
	{
		return $this->language_id;
	}

	public function set_language_id($value)
	{
		$this->language_id = $value;
	}
}

?>