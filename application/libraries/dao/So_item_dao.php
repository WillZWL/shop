<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class So_item_dao extends Base_dao
{
	private $table_name="so_item";
	private $vo_class_name="So_item_vo";
	private $seq_name="";
	private $seq_mapping_field="";

	public function __construct()
	{
		parent::__construct();
		$this->set_accessory_catid_arr();
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

	public function set_accessory_catid_arr()
	{
		$this->accessory_catid_arr = $this->get_accessory_catid_arr();
	}

	public function get_accessory_catid_arr()
	{
		/* ======================================================================
			IMPORTANT!
			This will get all current category IDs that below to Complementary Accessories
			if there are more accessory cat ids in future,
			add it to this array $ca_catid_arr.
		========================================================================= */

		return $accessory_catid_arr = array("753");
	}

	public function calculate_popularity($past_days = 30)
	{
		$sql = 	"
				update price p inner join
				(
    				select
				    prod_sku, prod_name, platform_id, sum(d.qty) as cnt
    				FROM so h
				    INNER JOIN so_item d on h.so_no = d.so_no and d.create_on >= DATE_SUB(CURDATE(),INTERVAL ? DAY) and h.status >= 2
    				GROUP BY prod_sku, platform_id order by cnt DESC
				)
				b on p.sku = b.prod_sku and p.platform_id = b.platform_id set p.sales_qty = b.cnt
		";

		if($query = $this->db->query($sql,array($past_days)))
		{
			$this->db->query("commit;");
			return true;
		}
		return false;
	}

	public function get_items_w_name($where=array(), $option=array(), $classname="So_item_w_name_dto")
	{
		if(!$option["show_ca"])
		{
			# don't include complementary accessories
			$ca_catid = implode(',', $this->accessory_catid_arr);
			$where["p.cat_id not in ($ca_catid)"] = null;
		}

		$this->db->from('so_item AS soi');
		$this->db->join('(
							SELECT *
							FROM v_prod_items
							ORDER BY component_order
						) AS vpi', 'soi.prod_sku = vpi.prod_sku', 'LEFT');
		$this->db->join('product AS p', 'soi.prod_sku = p.sku', 'LEFT');
		$this->db->join('category AS c', 'p.cat_id = c.id', 'LEFT');
		$this->db->join('product AS p2', 'vpi.item_sku = p2.sku', 'LEFT');
		$this->db->join('freight_category AS fc', 'p2.freight_cat_id = fc.id', 'LEFT');
		if ((!empty($option["lang_id"])) && ($option["lang_id"] != 'en'))
		{
			$this->db->join('product_content AS p3', "soi.prod_sku = p3.prod_sku and p3.lang_id='" . $option["lang_id"] . "'", 'LEFT');
		}
		$this->db->group_by('soi.line_no');

		$this->db->where($where);

		if (empty($option["num_rows"]))
		{

			$this->include_dto($classname);

			if ((!empty($option["lang_id"])) && ($option["lang_id"] != 'en'))
			{
				$select_product_name = "COALESCE(p3.prod_name, soi.prod_name)";
			}
			else
				$select_product_name = "soi.prod_name";
			$this->db->select('soi.*, ' . $select_product_name . ' name, SUM(fc.weight) AS item_weight, vpi.item_sku AS main_prod_sku, vpi.image AS main_img_ext, c.name as cat_name');

//			print $this->db->_compile_select();
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
}

/* End of file so_item_dao.php */
/* Location: ./system/application/libraries/dao/So_item_dao.php */