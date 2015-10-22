<?php
namespace ESG\Panther\Dao;

class PricingRulesDao extends BaseDao
{
    private $table_name = 'pricing_rules';
    private $vo_class_name = 'PricingRulesVo';

    public function getVoClassname()
    {
        return $this->vo_class_name;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getPricingRules($where = [], $option = [], $classname = 'PricingRulesDto')
    {
		$this->db->from('pricing_rules AS pr');
		
		if (empty($option["orderby"])) {
            $option["orderby"] = "pr.country_id ASC";
        }
		 
		if (empty($option["num_rows"])) 
		{
			$this->db->select('pr.id, pr.country_id, pr.range_min, pr.range_max, pr.mark_up_value, pr.mark_up_type, 
									if(pr.mark_up_type = "A", "Absolute", "Percentage") as mark_up_desc,
									if(pr.monday = 1, "X", "") as monday, 
									if(pr.tuesday = 1, "X", "") as tuesday, 
									if(pr.wednesday = 1, "X", "") as wednesday, 
									if(pr.thursday = 1, "X", "") as thursday, 
									if(pr.friday = 1, "X", "") as friday, 
									if(pr.saturday = 1, "X", "") as saturday, 
									if(pr.sunday = 1, "X", "") as sunday,
									pr.create_on, pr.create_at, pr.create_by, pr.modify_on, pr.modify_at, pr.modify_by');
									
            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

			
			$this->db->where($where);
			$this->db->limit($option["limit"], $option["offset"]);
			
            if ($query = $this->db->get()) {
                $classname = ($classname) ? : $this->getVoClassname();
                $rs = [];
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }

                return $rs;
            }
		}
		else 
		{		
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return FALSE;
    }
	
	public function getPricingRulesByPlatform($where = [], $classname = 'PricingRulesDto')
    {
		$this->db->from('pricing_rules AS pr');		
        $this->db->join('platform_biz_var AS pbv', 'pbv.platform_country_id = pr.country_id', 'INNER');
	
		 
		$this->db->select('pr.id, pr.country_id, pr.range_min, pr.range_max, pr.mark_up_value, pr.mark_up_type');
								

		
		$this->db->where($where);
		
		if ($query = $this->db->get()) {
			$classname = ($classname) ? : $this->getVoClassname();
			$rs = [];
			foreach ($query->result($classname) as $obj) {
				$rs[] = $obj;
			}

			return $rs;
		}
		
        return FALSE;
    }
	
	public function getExistingRule ($where)
	{
		/*select count(*) as total
			from pricing_rules
			where country_id = "GB"
				and (50 between range_min and range_max or 75 between range_min and range_max)
				and ((1 and monday = 1)
				or (1 and tuesday = 1)
				or (1 and wednesday = 1)
				or (1 and thursday= 1)
				or (1 and saturday = 1))*/
				
		$this->db->from('pricing_rules');
		$this->db->select('COUNT(*) AS total');
		$this->db->where($where);
		if ($query = $this->db->get()) {
			//print $this->db->last_query();
			return $query->row()->total;
		}
	}
}
