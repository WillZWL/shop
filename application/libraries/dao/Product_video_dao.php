<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Product_video_dao extends Base_dao
{
	private $table_name="product_video";
	private $vo_class_name="Product_video_vo";
	private $seq_name="";
	private $seq_mapping_field="";

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

	public function get_video_list_w_country($sku="", $country_arr=array(), $num_rows=FALSE)
	{
		if($num_rows==TRUE)
		{
			$sql = "SELECT COUNT(*) AS total";
		}
		else
		{
			$sql = "SELECT *";
		}
		$sql .= "
				FROM product_video pv
				WHERE pv.sku = '$sku'
				";

		$is_first = TRUE;
		foreach($country_arr as $obj)
		{
			$sql .= ($is_first?" AND (":" OR ")."country_id='".$obj."'";
			$is_first = FALSE;

		}
		$sql .= ")
				ORDER BY pv.create_on DESC";

		$this->include_vo();

		if($num_rows== TRUE)
		{
			if ($query = $this->db->query($sql))
			{
				return $query->row()->total;
			}
		}
		else
		{
			$rs = array();

			if ($query = $this->db->query($sql))
			{
				foreach ($query->result($this->get_vo_classname()) as $obj)
				{
					$rs[] = $obj;
				}
				return (object) $rs;
			}
		}
		return FALSE;
	}

	public function get_best_selling_video_list($filter_column = '',
		$cat_id = 0, $day_count = 0, $limit = 0, $platform, $type, $src)
	{
		if (($filter_column === '' && $cat_id !== 0) || !is_numeric($cat_id)
			|| !is_numeric($day_count) || $day_count <= 0 || empty($platform))
		{
			return FALSE;
		}

		$cat_filter_str = '';
		$limit_str = '';
		$input_array = array($day_count);

		if ($cat_id !== 0)
		{
			$cat_filter_str = " AND p.$filter_column = ?";
			array_push($input_array, $cat_id);
		}

		if($src != 0)
		{
			$src_filter_str = " AND pv.src = $src";
		}

		if ($limit > 0)
		{
			$limit_str = "LIMIT ?";
			array_push($input_array, $limit);
		}

		$sql = "SELECT pv.*
				FROM product p
				JOIN product_video pv
					ON p.sku = pv.sku
				JOIN platform_biz_var pbv
					ON pbv.platform_country_id = pv.country_id AND pbv.selling_platform_id = '$platform'
				JOIN
				(
					SELECT so.platform_id, soi.prod_sku, SUM(soi.qty) ttl_qty
					FROM so_item AS soi
					INNER JOIN so ON (so.so_no = soi.so_no AND so.status > 2
						AND DATEDIFF(now(), so.create_on) <= 60 AND so.platform_id = '$platform')
					GROUP BY soi.prod_sku
				) a
				ON (p.sku = a.prod_sku{$cat_filter_str})
				WHERE p.website_status= 'I' AND p.website_quantity > 0 AND pv.type = '$type'
				$src_filter_str
				ORDER BY a.ttl_qty DESC
				$limit_str";

		$result = $this->db->query($sql, $input_array);
		//echo $this->db->last_query()."<br>";

		$this->include_vo();

		$result_arr = array();
		$classname = $this->get_vo_classname();

 		foreach ($result->result("object", $classname) as $obj)
		{
			array_push($result_arr, $obj);
		}

		return $result_arr;
	}

	public function get_best_selling_video_list_by_cat($filter_column = '',
		$cat_id = 0, $day_count = 0, $limit = 0, $platform, $type, $src)
	{
		if (($filter_column === '' && $cat_id !== 0) || !is_numeric($cat_id)
			|| !is_numeric($day_count) || $day_count <= 0 || empty($platform))
		{
			return FALSE;
		}

		$cat_filter_str = '';
		$limit_str = '';
		$input_array = array($day_count);

		if ($cat_id !== 0)
		{
			$cat_filter_str = " AND p.$filter_column = ?";
			array_push($input_array, $cat_id);
		}

		if($src !== 0)
		{
			$src_filter_str = " AND pv.src = '$src'";
		}

		if ($limit > 0)
		{
			$limit_str = "LIMIT ?";
			array_push($input_array, $limit);
		}

		$sql = "SELECT pv.*
				FROM product p
				JOIN product_video pv
					ON p.sku = pv.sku
				JOIN platform_biz_var pbv
					ON pbv.platform_country_id = pv.country_id AND pbv.selling_platform_id = '$platform'
				JOIN
				(
					SELECT so.platform_id, soi.prod_sku, SUM(soi.qty) ttl_qty
					FROM so_item AS soi
					INNER JOIN so ON (so.so_no = soi.so_no AND so.status > 2
						AND DATEDIFF(now(), so.create_on) <= 30 AND so.platform_id = '$platform')
					GROUP BY soi.prod_sku
				) a
				ON (p.sku = a.prod_sku{$cat_filter_str})
				WHERE p.status = 2 AND p.website_status= 'I' AND p.website_quantity > 0 AND pv.type = '$type'
				$src_filter_str
				ORDER BY a.ttl_qty DESC
				$limit_str";

		if($result = $this->db->query($sql, $input_array))
		{
			$this->include_vo();

			$result_arr = array();
			$classname = $this->get_vo_classname();

			foreach ($result->result("object", $classname) as $obj)
			{
				array_push($result_arr, $obj);
			}
			return $result_arr;
		}

		return FALSE;
	}

	public function get_display_video_list($where=array(), $option=array(), $classname="Display_video_list_dto")
	{
		if($where["platform_id"] == "")
		{
			return FALSE;
		}

		if($where["cat_id"] != "")
		{
			$cat_filter = " AND p.cat_id = ".$where["cat_id"];
		}

		if($where["sub_cat_id"] != "")
		{
			$sub_cat_filter = " AND p.sub_cat_id = ".$where["sub_cat_id"];
		}

		if($where["min_price"] != "")
		{
			$min_filter = " AND IF(pr2.price>0, pr2.price, pr.price*ex.rate) >= ".$where["min_price"];
		}

		if($where["max_price"] != "")
		{
			$max_filter = " AND IF(pr2.price>0, pr2.price, pr.price*ex.rate) <= ".$where["max_price"];
		}

		if($where["brand"] != "")
		{
			$brand_filter = " AND br.brand_name ='".$where["brand"]."'";
		}

		if($where["video_type"] != "")
		{
			$v_type_filter = " AND pv.type = '".$where["video_type"]."'";
		}

		if($where["video_src"] != "")
		{
			$v_src_filter = " AND pv.src = '".$where["video_src"]."'";
		}

		if($where["product_type"] != "")
		{
			$prod_type_filter = " AND pt.type = '".$where["product_type"]."'";
		}

		$sql = "SELECT pv.*, p.name, cat.id cat_id, cat.name cat_name, sub_cat.id sub_cat_id, sub_cat.name sub_cat_name, br.brand_name
				FROM product p
				JOIN product_video pv
					ON (p.sku = pv.sku)
				JOIN category cat
					ON (p.cat_id = cat.id)
				JOIN category sub_cat
					ON (p.sub_cat_id = sub_cat.id)
				JOIN brand br
					ON (p.brand_id = br.id)
				LEFT JOIN (price pr, v_default_platform_id vdp)
					ON (p.sku = pr.sku AND pr.platform_id = vdp.platform_id AND pr.listing_status = 'L')
				LEFT JOIN price pr2
					ON (p.sku = pr2.sku AND pr2.platform_id = '".$where["platform_id"]."')
				JOIN platform_biz_var pbv
					ON (pbv.selling_platform_id = '".$where["platform_id"]."')
				JOIN exchange_rate ex
					ON (ex.to_currency_id = pbv.platform_currency_id) AND (ex.from_currency_id = 'GBP')";

		if($where["product_type"] != "")
		{
			$sql .= "
					LEFT JOIN product_type pt
						ON (pt.sku = p.sku)";
		}

		/*
		if(($where["min_price"] != "" || $where["max_price"] != "") || ($option['orderby'] == 'IFNULL(pr2.price, pr.price*ex.rate) ASC' || $option['orderby'] == 'IFNULL(pr2.price, pr.price*ex.rate) DESC'))
		{
		$sql .= "
				LEFT JOIN price pr
					ON (p.sku = pr.sku AND pr.platform_id = 'WEBGB' AND pr.listing_status = 'l')
				LEFT JOIN price pr2
					ON (p.sku = pr2.sku AND pr2.listing_status = 'l'  AND pr2.platform_id = '".$where["platform_id"]."')
				JOIN exchange_rate ex
					ON (ex.to_currency_id = pbv.platform_currency_id) AND (ex.from_currency_id = 'GBP')";
		}
		*/
		if($option['orderby'] == 'a.sold_amount DESC')
		{
			$to_date = date("Y-m-d", time());
			$subtract = time() - (86400 * 30);
			$from_date = date("Y-m-d", $subtract);

			$sql .= "
				LEFT JOIN
				(
					SELECT soid.item_sku, SUM(soid.qty) as sold_amount
					FROM so
					JOIN so_item_detail soid
						ON (so.so_no  = soid.so_no)
					WHERE so.create_on > '$from_date 00:00:00' AND so.create_on < '$to_date 23:59:59'
					GROUP BY soid.item_sku
				)a
					ON (a.item_sku = p.sku)
				";
		}

		$sql .= "
				WHERE p.website_status= 'I' AND p.website_quantity > 0 AND p.status = 2 AND pr2.listing_status = 'L'
					AND pbv.platform_country_id = pv.country_id
					$cat_filter
					$sub_cat_filter
					$min_filter
					$max_filter
					$brand_filter
					$v_type_filter
					$v_src_filter
					$prod_type_filter";

		if(!$option['num_rows'])
		{
			if($option['orderby'])
			{
				$sql .= "
					ORDER BY ".$option['orderby'];
			}

			if($option['groupby'])
			{
				$sql = "SELECT a.cat_id, a.cat_name, a.sub_cat_id, a.sub_cat_name, a.brand_name, COUNT(*) as count
						FROM
						(
							$sql
						)a
						GROUP BY ".$option['groupby'];
			}

			if($where['limit'])
			{
				$sql .= " LIMIT ".$where['limit'];
			}
			if($where['offset'])
			{
				$sql .= " OFFSET ".$where['offset'];
			}

			if($result = $this->db->query($sql))
			{
				$this->include_dto($classname);

				$result_arr = array();


				foreach ($result->result("object", $classname) as $obj)
				{
					array_push($result_arr, $obj);
				}

				return $result_arr;
			}
		}
		else
		{
			$sql = "
					SELECT COUNT(*) AS total
					FROM
					(
						$sql
					)t
					";
			if ($query = $this->db->query($sql))
			{
				return $query->row()->total;
			}
		}
		return FALSE;
	}
}

/* End of file Product_video_dao.php */
/* Location: ./system/application/libraries/dao/Product_video_dao.php */