<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Email_referral_list_dao extends Base_dao
{
    private $table_name = "email_referral_list";
    private $vo_class_name = "Email_referral_list_vo";
    private $seq_name = "";
    private $seq_mapping_field = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_vo_classname()
    {
        return $this->vo_class_name;
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_all_email_referral_list($where = '', $option = '', $classname = 'email_referral_w_client_dto')
    {
        $this->db->from("email_referral_list erl");
        $this->db->join("client as c", "c.email = erl.email", "LEFT");
        $where['erl.status'] = 1;
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "erl.id, erl.email, c.id as client_id, c.forename, c.surname, c.address_1, c.address_2, c.address_3, c.postcode, c.city, c.state, c.country_id, c.tel_1, c.tel_2, c.tel_3, c.create_on, c.create_at");
    }
}


