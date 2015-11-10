<?php
namespace ESG\Panther\Dao;

class ReleaseOrderHistoryDao extends BaseDao
{
    private $tableName = "release_order_history";
    private $voClassName = "ReleaseOrderHistoryVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function orderReleaseActivityLog($where, $option = [], $classname = "OrderReleaseActivityLogDto")
    {
        $this->db->from("(select temp5.so_no, temp5.create_at, temp5.create_on, temp5.create_by, release_reason from release_order_history temp5
                inner JOIN
                    (select so_no, max(create_on) as create_on from release_order_history group by so_no)
                    as temp4
                    on temp4.so_no = temp5.so_no and temp4.create_on=temp5.create_on
                ) as roh

                inner join

                (select temp3.so_no, temp3.create_on, create_at, create_by, reason from so_hold_reason temp3
                inner join
                    (select so_no, max(create_on) as create_on from so_hold_reason group by so_no)
                    as temp1
                    on temp1.so_no = temp3.so_no and temp1.create_on=temp3.create_on
                ) as sohr
                on roh.so_no = sohr.so_no");

        return $this->common_get_list($classname, $where, $option, "roh.so_no as order_number,sohr.reason as hold_reason, sohr.create_on as hold_date, sohr.create_by as hold_by,roh.release_reason as release_reason,roh.create_on as release_date, roh.create_at as release_at, roh.create_by as release_by");
    }
}


