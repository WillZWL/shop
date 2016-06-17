<?php
namespace ESG\Panther\Dao;

class EmailReferralListDao extends BaseDao
{
    private $tableName = "email_referral_list";
    private $voClassname = "EmailReferralListVo";

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
        return $this->voClassname;
    }

    public function getAllEmailReferralList($where = '', $option = '', $classname = 'EmailReferralWithClientDto')
    {
        $this->db->from("email_referral_list erl");
        $this->db->join("client as c", "c.email = erl.email", "LEFT");
        $where['erl.status'] = 1;
        return $this->commonGetList($classname, $where, $option, "erl.id, erl.email, c.id as client_id, c.forename, c.surname, c.address_1, c.address_2, c.address_3, c.postcode, c.city, c.country_id, c.tel_1, c.tel_2, c.tel_3, c.create_at");
    }
}