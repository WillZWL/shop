<?php
namespace ESG\Panther\Dao;

class SoCompensationDao extends BaseDao
{
    private $tableName = "so_compensation";
    private $voClassname = "SoCompensationVo";

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassname;
    }

    public function getOrdersEligibleForCompensation($where = [], $option = [], $classname = "CompensationOrderDto")
    {
        $this->db->from("so");
        $this->db->where(["so.status = 3" => null, "so.refund_status" => 0, "so.hold_status" => 0]);

        return $this->commonGetList($classname, $where, $option, "so.*");
    }

    public function getCompensationSoList($where = [], $option = [], $classname = "RequestOrderCompensationDto")
    {
        $this->db->from("so");
        $this->db->join("so_compensation AS cp", "so.so_no = cp.so_no", "INNER");
        $this->db->join("(
                            SELECT * FROM
                            (
                                SELECT compensation_id, so_no, note, create_on
                                FROM so_compensation_history cph
                                WHERE status = 1
                                ORDER BY create_on DESC
                            )a
                            GROUP BY a.so_no
                         )cph", "so.so_no = cph.so_no AND cp.id = cph.compensation_id", "INNER");
        $this->db->join("product AS p", "p.sku = cp.item_sku", "INNER");
        $this->db->where(["so.hold_status" => 1, "cp.status" => 1]);

        return $this->commonGetList($classname, $where, $option, "cp.id compensation_id, so.so_no, so.platform_id, cp.item_sku, p.name prod_name, cph.note, cph.create_on request_on");
    }

    public function getOrderCompensatedItem($where = [], $option = [], $classname = "CompensationItemDto")
    {
        $this->db->from("so");
        $this->db->join("so_compensation AS cp", "so.so_no = cp.so_no", "INNER");
        $this->db->join("product AS p", "p.sku = cp.item_sku", "INNER");
        $this->db->join("price AS pr", "pr.sku = p.sku and pr.platform_id=so.platform_id", "LEFT");
        $this->db->where(["cp.status" => 1]);

        return $this->commonGetList($classname, $where, $option, "so.so_no, so.platform_id, cp.item_sku, p.name prod_name, so.currency_id, pr.price");
    }
}


