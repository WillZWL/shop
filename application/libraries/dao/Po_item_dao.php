<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

Class Po_item_dao extends Base_dao
{
	private $table_name="po_item";
	private $vo_class_name="Po_item_vo";
	private $seq_name="";
	private $seq_mapping_field="";

	public function __construct(){
		parent::__construct();
	}

	public function get_vo_classname(){
		return $this->vo_class_name;
	}

	public function get_table_name(){
		return $this->table_name;
	}

	public function get_seq_name(){
		return $this->seq_name;
	}

	public function get_seq_mapping_field(){
		return $this->seq_mapping_field;
	}

	public function get_item_w_name($po_number="",$classname="")
	{
		if($po_number == "")
		{
			return FALSE;
		}
		else
		{
			$sql = 'SELECT p.line_number, p.sku, prod.name, p.order_qty, p.shipped_qty,p.unit_price, p.status, p.create_on,p.create_at,p.create_by,p.modify_on
					FROM po_item p
					JOIN product prod
						ON prod.sku = p.sku
					WHERE p.status = "A"
					AND p.po_number = ?
					ORDER BY p.line_number ASC';

			$this->include_dto($classname);
			$rs = array();

			if($query = $this->db->query($sql, $po_number))
			{
				foreach ($query->result($classname) as $obj)
				{
					$rs[] = $obj;
				}
				return (object) $rs;
			}
			else
			{
				return FALSE;
			}
		}
	}

	public function get_max_line_number($po_number)
	{
		if($po_number == "")
		{
			return FALSE;
		}
		else
		{
			$sql = "SELECT MAX(line_number) as maxline
					FROM po_item
					WHERE po_number = ?";

			if($query = $this->db->query($sql,$po_number))
			{
				foreach ($query->result($classname) as $obj)
				{
					$rs[] = $obj;
				}
				return $rs[0]->maxline;
			}
			else
			{
				return FALSE;
			}

		}
	}

	public function check_shipment_status($po_number)
	{
		if($po_number == "")
		{
			return FALSE;
		}
		else
		{
			$sql = "SELECT b.po_number, COUNT(b.line_number) as total, IFNULL(a.total,0) as completed, IFNULL(c.total,0) as in_progress
					FROM po_item b
					LEFT JOIN (SELECT po_number, COUNT(line_number) as total
							   FROM po_item
							   WHERE status = 'A'
							   AND order_qty = shipped_qty
							   GROUP BY po_number ) AS a
						ON a.po_number = b.po_number
					LEFT JOIN (SELECT po_number, COUNT(line_number) as total
							   FROM po_item
							   WHERE status = 'A'
							   AND order_qty > shipped_qty
							   AND shipped_qty > 0
							   GROUP BY po_number) AS c
						ON b.po_number = c.po_number
					WHERE b.po_number = ?
					LIMIT 1";

			if($query = $this->db->query($sql,$po_number))
			{
				foreach($query->result($classname) as $obj)
				{
					$rs[0] = $obj;
				}
				return array("total"=>$rs[0]->total,"completed"=>$rs[0]->completed,"in_progress"=>$rs[0]->in_progress);
			}
			else
			{
				return FALSE;
			}
		}
	}

	function get_outstanding($po_number,$line_number)
	{
		$sql = "SELECT poi.po_number, poi.line_number, (order_qty - shipped_qty) AS outstanding
				FROM po_item poi
				WHERE poi.po_number= ? AND poi.line_number=?
				LIMIT 1";

		$rs = array();
		if($query = $this->db->query($sql, array($po_number, $line_number)))
		{
			foreach($query->result("object","") as $obj)
			{
				$rs[0] = $obj;
			}
			return $rs[0]->outstanding;
		}
		return 0;
	}

	function compare_qty($po_number = "")
	{
		if($po_number == "")
		{
			return FALSE;
		}

		$sql = "SELECT SUM(order_qty) AS order_qty, SUM(shipped_qty) AS shipped_qty
				FROM po_item
				WHERE po_number = ?";


		if($query = $this->db->query($sql,$po_number))
		{
			return ($query->row()->order_qty == $query->row()->shipped_qty?TRUE:FALSE);
		}

		return FALSE;
	}

}

/* End of file purchase_order_dao.php */
/* Location: ./system/application/libraries/dao/Purchase_order_dao.php */
?>