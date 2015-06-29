<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Client_dao extends Base_dao
{
    private $table_name = "client";
    private $vo_class_name = "Client_vo";
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

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_client_last_order($where, $option, $classname = 'so_last_order_w_client_dto')
    {
        $this->db->from("so");
        $this->db->join("client as c", "c.id = so.client_id", "INNER");
        $this->include_dto($classname);
        return $this->common_get_list($where, $option, $classname, "so.*, c.email, c.tel_1, c.tel_2, c.tel_3, c.title");
    }

    public function update_password($client_id = '', $new_en_password = '')
    {
        if (empty($client_id) || empty($new_en_password)) {
            return 0; // Means fail
        }

        return $this->db->update($this->get_table_name(),
            array('id' => $client_id, 'password' => $new_en_password));
    }

    /*
     * This function is not used by anyone at the moment.  If someone starts
     * using this, please remove this comment.
     *
     * Author: Trunks
     */

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_new_vip_customer_list()
    {
        $this->db->from("(
                            select so.so_no, so.platform_id, so.client_id, so.refund_status, so.dispatch_date, SUM(so.amount * so.rate) ttl_amt, count(*) ttl
                            from so
                            join selling_platform sp
                                on so.platform_id = sp.id
                            where sp.type IN ('WEBSITE', 'SKYPE') AND so.status = 6
                            group by client_id
                        )a");
        $this->db->join("client c", "c.id = a.client_id", "INNER");
        $this->db->where(array("a.ttl >=" => 3, "a.ttl_amt >" => 50, "a.refund_status" => 0, "a.dispatch_date < NOW() - INTERVAL 3 WEEK" => null, "c.vip" => 0, "c.email NOT IN ('alice@eservicesgroup.net','fabrice.boissat@4d.com','garry@ortus.com.au','info@ortus.com.au','leo@eservicesgroup.net','marc.hilko@letsaskamerica.tv','nic@eservicesgroup.net','shakhil24@hotmail.com')" => null));
        $this->db->select();

        if ($query = $this->db->get()) {
            foreach ($query->result("array", $classname) as $obj) {
                $rs[] = $obj['client_id'];
            }
            return $rs;
        }
        return false;
    }
}

/* End of file client_dao.php */
/* Location: ./system/application/libraries/dao/Client_dao.php */