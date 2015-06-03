<?php

include_once 'Base_dao.php';

class Temp_barcode_mapping_dao extends Base_dao
{
	private $table_name="temp_barcode_mapping";
	private $vo_classname="Temp_barcode_mapping_vo";
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

	public function get_barcode($sku, $country_id)
	{
		$sql = "SELECT bar.*
				FROM temp_barcode_mapping bar
				JOIN sku_mapping map
					ON map.ext_sku = bar.master_sku AND map.status = 1 AND map.ext_sys = 'WMS'
				WHERE map.sku = ?
				LIMIT 1";

		if($query = $this->db->query($sql, $sku))
		{
			foreach($query->result() as $row)
			{
				switch($country_id)
				{
					case 'US':
					case 'AU':
						$ean = $row->ean_us;
						break;
					default:
						$ean = $row->ean;
				}
				$upc = $row->upc;
				$mpn = $row->mpn;

				$res = array("ean"=>$ean, "mpn"=>$mpn, "upc"=>$upc);
			}
			return $res;
		}
		return FALSE;
	}
}

/* End of file temp_barcode_mapping_dao.php */
/* Location: ./app/libraries/dao/Temp_barcode_mapping_dao.php */