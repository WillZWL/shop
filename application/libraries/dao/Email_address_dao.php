<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'Base_dao.php';

class Email_address_dao extends Base_dao
{
	private $table_name = "email_address";
	private $vo_class_name = "Email_address_vo";
	private $seq_name = "";
	private $seq_mapping_field = "";

	public function __construct()
	{
		parent::__construct();
	}

	public function get_vo_classname()
	{
		return $this->vo_class_name;
	}

	public function get_table_name()
	{
		return $this->table_name;
	}

	public function get_seq_name()
	{
		return $this->seq_name;
	}

	public function get_seq_mapping_field()
	{
		return $this->seq_mapping_field;
	}

	public function get_email_address($func_id)
	{
		$this->db->select('email');
		$this->db->from('email_address');
		$this->db->where('func_id', $func_id);
		$this->db->limit(1);

		if ($query = $this->db->get())
		{
			if ($query = $this->db->get())
			{
				return $query->row()->email;
			}
		}

		return FALSE;
	}

	public function get_email_address_list($func_id, $type = "array")
	{
		$this->db->select('email');
		$this->db->from('email_address');
		$this->db->where('func_id', $func_id);

		if ($query = $this->db->get())
		{
			$rs = array();
			if ($query->num_rows() > 0)
			{
				foreach ($query->result() as $row)
				{
					$rs[] = $row->email;
				}
			}
			return $type == "array" ? $rs : @implode(', ', $rs);
		}

		return FALSE;
	}
}

/* End of file email_address_dao.php */
/* Location: ./system/application/libraries/dao/Email_address_dao.php */