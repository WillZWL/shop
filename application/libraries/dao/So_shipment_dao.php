<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class So_shipment_dao extends Base_dao
{
	private $table_name="so_shipment";
	private $vo_class_name="So_shipment_vo";
	private $seq_name="customer_shipment";
	private $seq_mapping_field="sh_no";

	public function get_tracking_info_list($where=array(), $option=array(), $classname="So_shipment_vo")
	{
		$this->db->select('so.*');
		$this->db->from('so_shipment AS so');
		$this->db->join('interface_tracking_info AS iti', 'so.sh_no = iti.sh_no', 'INNER');
		$this->db->where($where);

		$option["limit"] = -1;

		if (empty($option["num_rows"]))
		{

			$this->include_vo($classname);

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

			$this->db->select('so.*');

			if ($query = $this->db->get())
			{
				foreach ($query->result($classname) as $obj)
				{
					$rs[] = $obj;
				}
				return (object) $rs;
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

	public function get_shn_list($type, $service="")
	{
		if(!in_array($type,array("object","array")))
		{
			return FALSE;
		}

		$where = array();
		if($service != "")
		{
			$where["courier_id LIKE"] = $service.'%';
		}

		$option["limit"] = -1;
		$list = $this->get_list($where, $option);

		if($type == "object")
		{
			return $list;
		}
		else
		{
			$ret = array();
			foreach($list as $obj)
			{
				$ret[$obj->get_sh_no()] = 1;
			}
			unset($list);
			return $ret;
		}
	}

	public function get_shipped_list($where=array(), $option=array(), $classname="Soid_prodname_dto")
	{

		$this->db->select('sosh.sh_no, soal.so_no, soal.line_no, soal.item_sku, soal.qty, sosh.tracking_no, sosh.create_on AS dispatch_date');

		$this->db->from('so_allocate AS soal');
		$this->db->join('so_shipment AS sosh', 'soal.sh_no = sosh.sh_no', 'INNER');
		$this->db->where(array('soal.status'=>3));

		if ($where)
		{
			$this->db->where($where);
		}

		if (empty($option["num_rows"]))
		{
			$this->include_dto($classname);

			if (isset($option["orderby"]))
			{
				$this->db->order_by($option["orderby"]);
			}

			if (empty($option["limit"]))
			{
				$option["limit"] = "";
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
				foreach ($query->result($classname) as $obj)
				{
					$rs[] = $obj;
				}
				return (object) $rs;
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

	public function get_shipping_info($where=array(), $option=array(), $classname="So_shipment_vo")
	{

		$this->db->select('sosh.*');
		$this->db->from('so_allocate AS soal');
		$this->db->join('so_shipment AS sosh', 'sosh.sh_no = soal.sh_no', 'INNER');
		$this->db->where($where);

		$option["limit"] = -1;

		if (empty($option["num_rows"]))
		{

			$this->include_vo($classname);

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
				foreach ($query->result($classname) as $obj)
				{
					$rs = $obj;
					return (object) $rs;
				}
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

	public function get_shipped_summary($start_date, $end_date)
	{
		if ($start_date == $end_date)
		{
			$start_date .= " 00:00:00";
			$end_date .= " 23:59:59";
		}

		$sql = "
			select sm.ext_sku master_sku, sum(sb.amount*ex.rate) total_amount_hkd, sum(sb.qty) total_quantity from so
			inner join so_item sb on so.so_no = sb.so_no and so.biz_type <> 'SPECIAL' and so.status = 6 and so.hold_status = 0
			and so.dispatch_date >= ? and so.dispatch_date <= ?
			inner join sku_mapping sm on sm.ext_sys = 'WMS' and sm.sku = sb.prod_sku
			inner join exchange_rate ex on ex.from_currency_id = so.currency_id and ex.to_currency_id = 'HKD'
			group by sb.prod_sku
		";

		// turning $past_day to integer is enough for any sanitization
		$past_day = intval($past_day);

		// $result = $this->db->query($sql, array($past_day));
		$result = $this->db->query($sql, array($start_date, $end_date));
		if ($result == null) $rs = array();
		foreach ($result->result() as $row)
		{
			$rs[] = $row;
		}
		return $rs;
	}

	public function gen_dhl_shipment_tracking_feed()
	{
		$sql = "
				select sosh.sh_no, sosh.tracking_no, so.delivery_name, so.delivery_address,  so.delivery_city as delivery_city, so.delivery_state as delivery_state,  so.delivery_postcode, so.delivery_country_id, vpo.cc_desc, so.cost as amount, so.so_no, so.currency_id
				from so_shipment sosh
				LEFT JOIN so on so.so_no = SUBSTR(sosh.sh_no, 1, CHAR_LENGTH(sosh.sh_no)-3)
				LEFT JOIN so_item_detail soid on soid.so_no = so.so_no
				LEFT JOIN v_prod_overview_wo_shiptype vpo
					ON (vpo.sku = soid.item_sku AND so.platform_id = vpo.platform_id)
				WHERE sosh.courier_feed_sent is null and (sosh.courier_id = 'deutsch-post' or sosh.courier_id = 'deutsche-post') and sosh.status = 2 and date(sosh.modify_on) >= '2014-06-24'
			";

		$classname = "dhl_shipment_tracking_dto";
		$this->include_dto("dhl_shipment_tracking_dto");

		$rs = array();

		if ($query = $this->db->query($sql))
		{
			foreach ($query->result($classname) as $obj)
			{
				$rs[] = $obj;
			}
		}

		return $rs;

	}

}

/* End of file so_shipment_dao.php */
/* Location: ./system/application/libraries/dao/So_shipment_dao.php */