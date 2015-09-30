<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once "Base_dao.php";

class Voucher_dao extends Base_dao
{
	private $table_name="voucher";
	private $vo_classname="Voucher_vo";
	private $seq_name="";
	private $seq_mapping_field="";

	public function __construct()
    {
        parent::__construct();
    }

	public function get_table_name()
	{
		return $this->table_name;
	}

	public function get_vo_classname()
	{
		return $this->vo_classname;
	}

	public function get_seq_name()
	{
		return $this->seq_name;
	}

	public function get_seq_mapping_field()
	{
		return $this->seq_mapping_field;
	}
	
	public function get_voucher_w_detail($where=array(), $option=array(), $classname="voucher_w_detail_dto")
	{
		$this->db->from('voucher v');
		$this->db->join('voucher_detail vd', 'v.id = vd.voucher_id', 'INNER');
		
		if ($where)
		{
			$this->db->where($where);
		}
		
		if (empty($option["num_rows"]))
		{

			$this->include_dto($classname);

			$this->db->select('vd.id, v.id voucher_id, v.type, v.party, v.expire_date, vd.code, vd.distributed, vd.total_distribution, vd.status');

			if (isset($option["orderby"]))
			{
				$this->db->order_by($option["orderby"]);
			}

			if (empty($option["limit"]))
			{
				$option["limit"] = $this->rows_limit;
			}

			elseif ($option["limit"] == -1)
			{
				$option["limit"] = "";
			}

			if (!isset($option["offset"]))
			{
				$option["offset"] = 0;
			}

			if ($this->rows_limit != "")
			{
				$this->db->limit($option["limit"], $option["offset"]);
			}

			$rs = array();

			if ($query = $this->db->get())
			{
				foreach ($query->result("object", $classname) as $obj)
				{
					$rs[] = $obj;
				}
				return $rs;
			}
		}
		else
		{
			$this->db->select('COUNT(*) AS total');
			if ($query = $this->db->get())
			{
				return $query->row()->total;
			}
		}
		return FALSE;
	}

}
/* End of file voucher_dao.php */
/* Location: ./app/libraries/dao/voucher_dao.php */