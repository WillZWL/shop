<?php
namespace ESG\Panther\Dao;

class DelayedOrderDao extends BaseDao
{
    private $tableName = "delayed_order";
    private $voClassName = "DelayedOrderVo";

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

    public function getAllMinorDelayOrder($where = [], $option = [])
    {
        $this->db->from("so");
        $this->db->select("so.so_no");
        $this->db->join("so_payment_status sops", "sops.so_no = so.so_no", "INNER");
        return $this->commonGetList([], $where, $option);
    }

    public function hasOosStatus($where = [], $option = [])
    {
        $this->db->from("so_hold_reason sohr");
        $this->db->select("sohr.so_no");
        return $this->commonGetList([], $where, $option);
    }

    public function getDelayOrder($where = [], $option = [])
    {
        $this->db->from("delayed_order deor");
        $this->db->join("so", "so.so_no = deor.so_no", "INNER");
        $this->db->join("client", "client.id = so.client_id", "INNER");
        $this->db->select("deor.so_no, client.forename, client.country_id, so.platform_id, so.client_id, so.lang_id");
        return $this->commonGetList([], $where, $option);
    }

    public function isDelayOrder($where = [], $option = [])
    {
        $this->db->from("delayed_order deor");
        return $this->commonGetList($this->getVoClassname(), $where, $option);
    }
}

