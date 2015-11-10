<?php
namespace ESG\Panther\Dao;

class SoCompensationHistoryDao extends BaseDao
{
    private $tableName = "so_compensation_history";
    private $voClassname = "SoCompensationHistoryVo";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }

    public function getNotificationEmail($compensation_id)
    {
        $this->db->from("so_compensation_history cph");
        $this->db->join("user AS usr", "usr.id = cph.create_by");
        $this->db->where(["cph.compensation_id" => $compensation_id, "cph.status" => 1]);
        $this->db->select("usr.email");
        if ($query = $this->db->get()) {
            return $query->row()->email;
        }

        return false;
    }
}


