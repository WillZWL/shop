<?php
namespace ESG\Panther\Dao;

class BatchDao extends BaseDao
{
    private $tableName = "batch";
    private $voClassName = "BatchVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getBatchList($where = array(), $option = array(), $classname = "Batch_dto")
    {

        $this->db->from('batch AS b');
        $this->db->join('(
                        SELECT id, TIMEDIFF(end_time, create_on) AS duration
                        FROM batch
                        ) AS bd', 'b.id = bd.id', 'INNER');

        $this->db->where($where);

        if (empty($option["num_rows"])) {

            $this->db->select('b.*, bd.duration');

            $this->include_dto($classname);

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

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            $rs = array();

            if ($query = $this->db->get()) {
                foreach ($query->result($classname) as $obj) {
                    $rs[] = $obj;
                }
                return (object)$rs;
            }

        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }
        return FALSE;
    }

    public function getBatchStatusForOrder($batch_id = "")
    {
        if ($batch_id == "") {
            return FALSE;
        }

        $sql = " SELECT a.batch_id, COUNT(a.batch_id) as complete, b.total
             FROM interface_so a
             JOIN (SELECT batch_id, COUNT(batch_id) as total
                FROM interface_so
                WHERE batch_id = ?
                GROUP BY batch_id) AS b
                ON b.batch_id = a.batch_id
             WHERE a.batch_status = 'S'
             AND a.batch_id = ?
             GROUP BY a.batch_id
             LIMIT 1
            ";

        if ($query = $this->db->query($sql, array($batch_id, $batch_id))) {

            return array("completed" => $query->row()->complete, "total" => $query->row()->total);
        }
        return FALSE;
    }

}



