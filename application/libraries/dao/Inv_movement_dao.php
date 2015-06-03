<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

Class Inv_movement_dao extends Base_dao
{
	private $table_name="inv_movement";
	private $vo_class_name="Inv_movement_vo";
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

	public function get_list_array2($wh,$status="IT")
	{
		$ret = $this->get_list(array("to_location"=>$wh, "status"=>$status));
		if($ret === FALSE)
		{
			return FALSE;
		}
		else
		{
			$result = array();
			if(!empty($ret))
			{
				foreach ($ret as $obj)
				{
					$result[$obj->get_ship_ref()] = $obj;
				}
			}
			return $result;
		}
	}


	public function get_outstanding_open_shipment($wh,$where=array(),$option=array(),$status="", $classname="Wh_confirm_shipment_dto")
	{
		$this->db->from('supplier_shipment ss');
		$im_join = "(SELECT log_sku, sku, qty as shipped_qty
					 FROM inv_movement
					 WHERE to_location='$wh'";
		if($status != "")
		{
			$im_join .= " AND status = '$status' ";
		}
		$im_join .= ") AS im";

		$this->db->join("(SELECT trans_id,ship_ref, log_sku, sku, qty as shipped_qty
						  FROM inv_movement
						  WHERE to_location = '$wh' ) AS im","im.ship_ref = ss.shipment_id","INNER");
		$this->db->join("product p","p.sku = im.sku","INNER");
		$this->db->join("po_item_shipment pois","pois.sid = ss.shipment_id","INNER");
		$this->db->join("purchase_order po","po.po_number = pois.po_number","INNER");
		$this->db->join("supplier s","s.id = po.supplier_id ","INNER");

		$this->db->where($where);

		$this->include_dto($classname);

		$volist = array();
		$ssvolist = array();
		include_once APPPATH."libraries/dao/Supplier_shipment_dao.php";
		$ss_dao = new Supplier_shipment_dao();

		if(empty($option["num_rows"]))
		{
			$this->db->group_by("ss.shipment_id,im.log_sku");

			//$this->db->select('ss.shipment_id, im.trans_id, im.log_sku, im.sku, p.name as prod_name,im.shipped_qty, po.supplier_id, s.name as supplier_name');
			$this->db->select('DISTINCT(ss.shipment_id)');

			$this->include_dto($classname);

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
				echo $this->db->last_query();
				foreach ($query->result($classname) as $obj)
				{
					$rs[] = $obj;
					$volist[$obj->get_trans_id()] = $this->get(array("trans_id"=>$obj->get_trans_id()));
					$ssvolist[$obj->get_shipment_id()] = $ss_dao->get(array("shipment_id"=>$obj->get_shipment_id()));

				}
				return array("result"=>(object)$rs,"volist"=>$volist,"ssvolist"=>$ssvolist);
			}
		}
		else
		{
			$this->db->select('COUNT(DISTINCT ss.shipment_id) AS total');
			if ($query = $this->db->get())
			{
				return $query->row()->total;
			}
		}
		return FALSE;
	}

	public function get_outstanding_open_shipment_new($wh,$where=array(),$option=array(),$status="", $classname="Wh_confirm_shipment_dto")
	{

		$sql2 = "	SELECT DISTINCT(ss2.shipment_id)
					FROM (`supplier_shipment` ss2)
					INNER JOIN (SELECT trans_id,ship_ref, sku, qty as shipped_qty
								FROM inv_movement
								WHERE to_location = '$wh' ) AS im
						ON `im`.`ship_ref` = `ss2`.`shipment_id`
					INNER JOIN `product` p
						ON `p`.`sku` = `im`.`sku`
					INNER JOIN `po_item_shipment` pois
						ON `pois`.`sid` = `ss2`.`shipment_id`
					INNER JOIN `purchase_order` po
						ON `po`.`po_number` = `pois`.`po_number`
					INNER JOIN `supplier` s
						ON `s`.`id` = `po`.`supplier_id`
					WHERE 1";


		foreach($where as $key=>$value)
		{
			$sql2 .= " AND ".$key."'".$value."'";
		}
		$sql = "SELECT ss.courier,ss.tracking_no, ss.shipment_id
				FROM supplier_shipment ss
				JOIN(".$sql2.") AS ssb
					ON ssb.shipment_id = ss.shipment_id
				WHERE ss.status='IT'";
		$this->include_dto($classname);

		if (isset($option["orderby"]))
		{
			$sql .= " ORDER BY ".$option["orderby"];
		}

		if ($option["limit"] == -1)
		{
			$option["limit"] = "";
		}

		if (!isset($option["offset"]))
		{
			$option["offset"] = 0;
		}

		if ($this->rows_limit != "" && $option["limit"] != "")
		{
			$sql .= " LIMIT ".$option["limit"].($option["offset"]?",".$option["offset"]:"");
		}

		$rs = array();

		$volist = array();
		$ssvolist = array();
		include_once APPPATH."libraries/dao/Supplier_shipment_dao.php";
		$ss_dao = new Supplier_shipment_dao();
		include_once APPPATH."libraries/dao/Po_item_shipment_dao.php";
		$pois_dao = new Po_item_shipment_dao();


		if($query = $this->db->query($sql))
		{
			$cnt = 0;
			foreach ($query->result($classname) as $obj)
			{
				$rs[] = $obj;
				$cnt++;
				//$volist[$obj->get_trans_id()] = $this->get(array("trans_id"=>$obj->get_trans_id()));
				$ssvolist[$obj->get_shipment_id()] = $ss_dao->get(array("shipment_id"=>$obj->get_shipment_id()));
				$tmp = $this->get_shipment_detail($obj->get_shipment_id());

				$obj->set_detail($tmp);
				$item_list = explode("::",$tmp);
				foreach($item_list as $value)
				{
					$item_arr = explode("||",$value);
					$volist[$item_arr[6]] = $this->get(array("trans_id"=>$item_arr[6]));
					$pois_where = array("sid"=>$obj->get_shipment_id(),"po_number"=>$item_arr[0],"line_number"=>$item_arr[5]);
					$poislist[$obj->get_shipment_id()][$item_arr[0]][$item_arr[5]] = $pois_dao->get($pois_where);
				}

			}

			return array("result"=>(object)$rs,"volist"=>$volist,"ssvolist"=>$ssvolist,"poislist"=>$poislist,"total"=>$cnt);
		}
		return FALSE;
	}

	private function get_shipment_detail($shipment_id = "")
	{
		if($shipment_id == "")
		{
			return FALSE;
		}
		$sql = "SELECT pois.sid, CONCAT(pois.po_number,'||',b.sku,'||',c.name,'||',CAST(pois.qty AS CHAR),'||',d.name,'||',CAST(pois.line_number AS CHAR),'||',CAST(im.trans_id AS CHAR)) AS detail, d.name sname
						FROM po_item_shipment pois
						JOIN po_item b
							ON pois.po_number = b.po_number
							AND pois.line_number = b.line_number
						JOIN product c
							ON b.sku = c.sku
						JOIN purchase_order po
							ON pois.po_number = po.po_number
						JOIN supplier d
							ON 	d.id = po.supplier_id
						JOIN inv_movement im
							ON im.trans_id = pois.invm_trans_id
						WHERE pois.sid = ?
						GROUP BY pois.line_number
						ORDER BY pois.line_number ASC";

		if($query = $this->db->query($sql, $shipment_id))
		{
			$tmp = array();
			foreach($query->result("object") as $tobj)
			{
				$tmp[] = $tobj->detail;
			}

			return implode("::",$tmp);
		}
		return FALSE;
	}

	public function get_shipment($wh,$where=array(),$option=array(),$status="", $classname="Wh_confirm_shipment_dto")
	{
		$sql2 = "	SELECT DISTINCT(ss2.shipment_id)

					FROM (`supplier_shipment` ss2)
					INNER JOIN (SELECT trans_id,ship_ref, sku, qty as shipped_qty
								FROM inv_movement
								WHERE to_location = '$wh' ) AS im
						ON `im`.`ship_ref` = `ss2`.`shipment_id`
					INNER JOIN `product` p
						ON `p`.`sku` = `im`.`sku`
					INNER JOIN `po_item_shipment` pois
						ON `pois`.`sid` = `ss2`.`shipment_id`
					INNER JOIN `purchase_order` po
						ON `po`.`po_number` = `pois`.`po_number`
					INNER JOIN `supplier` s
						ON `s`.`id` = `po`.`supplier_id`
					WHERE 1";

		foreach($where as $key=>$value)
		{
			$sql2 .= " AND ".$key."'".$value."'";
		}
		$sql = "SELECT ss.shipment_id AS shipment_id,b.sku AS sku,c.name AS prod_name,CAST(pois.qty AS CHAR)AS ordered_qty,d.name AS supplier_name,CAST(pois.received_qty AS CHAR) AS received_qty, pois.reason_code AS reason,ss.remark AS remarks,ss.courier AS delivery_mode,ss.tracking_no AS tracking_no
				FROM supplier_shipment ss
				JOIN po_item_shipment pois
					ON pois.sid = ss.shipment_id
				JOIN po_item b
					ON pois.po_number = b.po_number
					AND pois.line_number = b.line_number
				JOIN product c
					ON b.sku = c.sku
				JOIN purchase_order po
					ON pois.po_number = po.po_number
				JOIN supplier d
					ON 	d.id = po.supplier_id
				JOIN inv_movement im
					ON im.trans_id = pois.invm_trans_id
				JOIN (".$sql2.") as ss2
					ON ss.shipment_id = ss2.shipment_id
				AND ss.status='IT' ";

		$this->include_dto($classname);

		if (isset($option["orderby"]))
		{
			$sql .= " ORDER BY ".$option["orderby"];
		}

		if ($option["limit"] == -1)
		{
			$option["limit"] = "";
		}

		if (!isset($option["offset"]))
		{
			$option["offset"] = 0;
		}

		if ($this->rows_limit != "" && $option["limit"] != "")
		{
			$sql .= " LIMIT ".$option["limit"].($option["offset"]?",".$option["offset"]:"");
		}

		if($query = $this->db->query($sql))
		{
			//var_dump($query);
			//var_dump($this->db->last_query());
			foreach ($query->result($classname) as $obj)
			{
				//$tmp[] = $this->get_shipment_detail($obj->get_shipment_id());
				$tmp[] = $obj;
			}
			return $tmp;
		}
		return FALSE;
	}

	public function get_inventory_movement($where=array(), $classname="Inv_mov_list_w_prod_name_dto")
	{

		$start_date = $where["start_date"]." 00:00:00";
		$end_date = $where["end_date"]." 23:59:59";

		$sql = "
				SELECT im.*,i.description
				FROM inv_movement im
				LEFT JOIN inv_status i
					ON (im.type = i.type AND im.status = i.status)
				WHERE ((im.create_on >= ? AND ? >= im.create_on ) OR (? >= im.create_on AND im.modify_on >= ?)) AND im.sku = ?
				";
		$this->include_dto($classname);

		$rs =array();

		if($query = $this->db->query($sql, array($start_date, $end_date, $start_date, $start_date, $where["sku"])))
		{
			//var_dump($this->db->last_query());
			//exit;
			foreach($query->result($classname) as $obj)
			{
				$rs[] = $obj;
			}
			return $rs;
		}
		return FALSE;
	}
}

/* End of file purchase_order_item_shipment_dao.php */
/* Location: ./system/application/libraries/dao/Purchase_order_item_shipment_dao.php */
?>