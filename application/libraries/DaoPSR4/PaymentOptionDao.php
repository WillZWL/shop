<?php
namespace ESG\Panther\Dao;

class PaymentOptionDao extends BaseDao
{
    private $tableName = "payment_option";
    private $voClassname = "PaymentOption";

    public function __construct() {
        parent::__construct();
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function getVoClassname() {
        return $this->voClassname;
    }

    public function getPaymentOption($where = [], $option = [], $classname = "PaymentOptionInfoDto") {
        $this->db->from("payment_option po");
        $this->db->join("payment_option_set pos", "pos.set_id=po.set_id and pos.status=1", 'INNER');
        $this->db->join("payment_option_set_content posc", "posc.set_id=pos.set_id and posc.status=1", 'INNER');
        $this->db->join("payment_option_card poc", "poc.code=posc.card_code and poc.status=1", 'INNER');
        $this->db->join("payment_gateway pg", "pg.payment_gateway_id=poc.payment_gateway_id and pg.status=1", 'INNER');

        return $this->commonGetList($classname, $where, $option, "platform_id, po.set_id, pos.name as set_name, posc.card_code, poc.payment_gateway_id, poc.card_id, poc.card_name, poc.card_image");
    }
}
