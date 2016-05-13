<?php
namespace ESG\Panther\Dao;

class DeliveryTimeDao extends BaseDao
{
    private $tableName = "delivery_time";
    private $voClassName = "DeliveryTimeVo";

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

    public function getDeliverytimeList($where = array())
    {
        $dto = "DeliveryTimeListDto";

        $this->db->select("
                            dt.id, dt.scenarioid, dt.country_id, dt.ship_min_day, dt.ship_max_day, dt.del_min_day, dt.del_max_day, dt.margin, dt.status AS dt_status,
                            dt.create_on, dt.create_at, dt.create_by, dt.modify_on, dt.modify_at, dt.modify_by,
                            lookds.name, lookds.description, lookds.status AS lookupscenario_status
                        ", false);
        $this->db->from("delivery_time AS dt");
        $this->db->join("lookup_delivery_scenario AS lookds", "dt.scenarioid = lookds.id", "INNER");
        $this->db->where($where);
        $this->db->where("dt.status = 1 AND lookds.status = 1");
        $this->db->order_by("lookds.id ASC");

        $rs = array();
        if ($query = $this->db->get()) {
            foreach ($query->result($dto) as $obj) {
                $rs[] = $obj;
            }
            return (object)$rs;
        }

        return FALSE;
    }

    public function getDeliverytimeObj($ctry_id, $scenarioid)
    {
        $dto = "DeliveryTimeListDto";
        $this->db->select("
                            dt.id, dt.scenarioid, dt.country_id, dt.ship_min_day, dt.ship_max_day, dt.del_min_day, dt.del_max_day, dt.margin, dt.status AS dt_status,
                            dt.create_on, dt.create_at, dt.create_by, dt.modify_on, dt.modify_at, dt.modify_by,
                            lookds.name, lookds.description, lookds.status AS lookupscenario_status
                        ", false);
        $this->db->from("delivery_time AS dt");
        $this->db->join("lookup_delivery_scenario AS lookds", "dt.scenarioid = lookds.id", "INNER");
        $this->db->where("dt.status = 1 AND lookds.status = 1");
        $this->db->where("dt.country_id", $ctry_id);
        $this->db->where("dt.scenarioid", $scenarioid);
        $this->db->order_by("lookds.id ASC");
        $this->db->limit(1);
        if ($query = $this->db->get()) {
            foreach ($query->result($dto) as $obj) {
                return (object)$obj;
            }
        }

        return FALSE;
    }

    public function getDeliveryScenarioList()
    {
        $this->db->from("lookup_delivery_scenario");
        $this->db->where("status = 1");
        $rs = array();

        if ($query = $this->db->get()) {
            foreach ($query->result() as $row) {
                $rs[] = $row;
            }
            return (object)$rs;
        }

        return FALSE;

    }


    public function bulkUpdateDeliveryScenarioByPlatform($platform_id, $scenarioid, $sku_list)
    {
        $ts = date("Y-m-d H:i:s");
        $ip = $_SERVER["REMOTE_ADDR"] ? $_SERVER["REMOTE_ADDR"] : "127.0.0.1";
        $id = empty($_SESSION["user"]["id"]) ? "system" : $_SESSION["user"]["id"];
        $this->db->trans_start();
        $where["platform_id"] = $platform_id;
        $where["sku IN ($sku_list)"] = null;
        $this->db->where($where);
        $this->db->update('price', array("delivery_scenarioid" => $scenarioid, "modify_on" => $ts, "modify_at" => $ip, "modify_by" => $id));
        $this->db->trans_complete();
        if ($this->db->trans_status() !== FALSE) {
            $affected = $this->db->affected_rows();
            return $affected;
        } else {
            return FALSE;
        }
        return FALSE;
    }

}


