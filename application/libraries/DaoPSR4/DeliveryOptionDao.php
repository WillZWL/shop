<?php
namespace AtomV2\Dao;

class DeliveryOptionDao extends BaseDao
{
    private $tableName = "delivery_option";
    private $voClassName = "DeliveryOptionVo";

    public function __construct()
    {
        parent::__construct();
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getVoClassname()
    {
        return $this->voClassName;
    }

    public function displayNameOf($courierId, $langId = "en")
    {
        $this->db->select('display_name');
        if ($query = $this->db->get_where($this->getTableName(), array("courier_id" => $courierId, "lang_id" => $langId), 1)) {
            return $query->row()->display_name;
        } else {
            return FALSE;
        }
    }
}


