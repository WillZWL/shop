<?php
namespace ESG\Panther\Dao;

Class InterfaceTrackingFeedDao extends BaseDao
{
    private $tableName="interface_tracking_feed";
    private $voClassName="InterfaceTrackingFeedVo";

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getSoAllocateShipment($where = [])
    {
        $this->db->from("so");
        $this->db->join("so_allocate soal", "so.so_no = soal.so_no", "INNER");
        $this->db->join("so_shipment sosh", "soal.sh_no = sosh.sh_no", "INNER");

        $this->db->group_by("so.so_no,sosh.sh_no");

        $select_str = "so.so_no,
                       sosh.sh_no,
                       sosh.courier_id,
                       sosh.tracking_no,
                       so.status,
                       soal.status soal_status,
                       sosh.status sosh_status,
                       so.hold_status,
                       so.refund_status";

        $this->db->select($select_str, FALSE);
        if($where) {
            $this->db->where($where);
        }
        $this->db->limit(1);

        $rs = [];
        if ($query = $this->db->get())
        {
            return $query->row(0, 'array');
        }

        return FALSE;
    }
}
