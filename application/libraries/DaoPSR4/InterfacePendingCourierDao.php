<?php
namespace ESG\Panther\Dao;

class InterfacePendingCourierDao extends BaseDao
{
    private $tableName = "interface_pending_courier";
    private $voClassName = "InterfacePendingCourierVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getConfirmPendingOrder($where=array(), $option=array(), $classname='InterfacePendingCourierVo')
    {
        $this->db->select("ipc.*");
        $this->db->from('interface_pending_courier as ipc');
        $this->db->join('so', 'ipc.so_no = so.so_no', 'INNER');
        $where['so.refund_status']='0';
        $where['so.hold_status']='0';
          
        if ($where) {
            $this->db->where($where);
        }
        if (empty($option["num_rows"])) {
            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }
            if (empty($option["limit"])) {
                $option["limit"] = "";
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }
            if (isset($option["so_no"])){
                $this->db->where_in("ipc.so_no",$option["so_no"]);
            }
            if ($so_no){
            $this->db->where_in("so.so_no",$so_no);
            }
            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }
            $rs = [];
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

    public function getCourierOrderByBatch($where=array(),$option=array(), $classname='CourierBatchOrderDto'){
        $this->db->select("ipc.*,ic.courier_order_status,ic.courier_parcel_id,ic.tracking_no,ic.error_message,ic.real_tracking_no,ic.print_nums,ic.last_print_on,ic.create_on as create_date,so.status,icm.manifest_id,fc.weight as prod_weight");

        $this->db->from('interface_pending_courier as ipc');
        $this->db->join('interface_courier_order as ic', 'ic.courier_order_id = ipc.so_no and ic.batch_id=ipc.batch_id', 'INNER');
        $this->db->join('interface_courier_manifest as icm',"icm.courier_batch_id=ipc.batch_id",'LEFT');
        $this->db->join('so','ipc.so_no = so.so_no','INNER');
        $this->db->join('product as p','p.sku = ipc.master_sku','LEFT');
        $this->db->join('freight_category as fc','fc.id = p.freight_cat_id','LEFT');
        $where["ic.status"]="1";

        if ($where) {
            $this->db->where($where);
        }
        if (empty($option["num_rows"])) {
            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }
            if (empty($option["limit"])) {
                $option["limit"] = "";
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }
            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }
            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }
            $rs = [];
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

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function getTableName()
    {
        return $this->tableName;
    }


}
