<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Ra_prod_prod_dao extends Base_dao
{
    private $table_name="ra_prod_prod";
    private $vo_classname="Ra_prod_prod_vo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_table_name()
    {
        return $this->table_name;
    }

    public function get_vo_classname()
    {
        return $this->vo_classname;
    }

    public function get_seq_name()
    {
        return $this->seq_name;
    }

    public function get_seq_mapping_field()
    {
        return $this->seq_mapping_field;
    }

    public function get_avail_ra_list($sku)
    {
/*      $sql =  "SELECT a.sku, p.sku ra_sku, p.name, p.website_status, p.image
                    FROM
                    (
                        SELECT rpp.sku, rpp.rcm_prod_id_1 ra_prod_id
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = '" . $sku . "'
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_2
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = '" . $sku . "'
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_3
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = '" . $sku . "'
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_4
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = '" . $sku . "'
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_5
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = '" . $sku . "'
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_6
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = '" . $sku . "'
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_7
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = '" . $sku . "'
                    ) a
                    JOIN product p
                        ON (p.sku = a.ra_prod_id AND p.website_status = 'I')
                    WHERE a.ra_prod_id IS NOT NULL";*/

        $sql =  'SELECT a.sku, p.sku ra_sku, p.name, p.website_status, p.image,
                        p.website_quantity, p.quantity, c.name cat_name
                    FROM
                    (
                        SELECT rpp.sku, rpp.rcm_prod_id_1 ra_prod_id
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = ?
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_2
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = ?
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_3
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = ?
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_4
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = ?
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_5
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = ?
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_6
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = ?
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_7
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = ?
                        UNION
                        SELECT rpp.sku, rpp.rcm_prod_id_8
                        FROM ra_prod_prod rpp
                        WHERE rpp.sku = ?
                        UNION
                        SELECT ? sku, rpp.sku ra_prod_id
                        FROM ra_prod_prod rpp
                        WHERE rpp.rcm_prod_id_1 = ?
                            OR rpp.rcm_prod_id_2 = ?
                            OR rpp.rcm_prod_id_3 = ?
                            OR rpp.rcm_prod_id_4 = ?
                            OR rpp.rcm_prod_id_5 = ?
                            OR rpp.rcm_prod_id_6 = ?
                            OR rpp.rcm_prod_id_7 = ?
                            OR rpp.rcm_prod_id_8 = ?
                    ) a
                    INNER JOIN product p
                        ON (p.sku = a.ra_prod_id AND p.website_status = \'I\' AND p.status = 2)
                    INNER JOIN category c
                        ON (p.cat_id = c.id)
                    WHERE a.ra_prod_id IS NOT NULL
                    ORDER BY p.cat_id';


        $result = $this->db->query($sql, array($sku,$sku,$sku,$sku,$sku,$sku,$sku,$sku,$sku,$sku,$sku,$sku,$sku,$sku,$sku,$sku,$sku));

        return $result->result_array();
    }

    public function get_prod_ra_prod_w_price($where = array(), $option = array())
    {
        $select_str = "*";
        $this->db->from('v_prod_ra_prod_w_price');
        include_once(APPPATH."libraries/vo/v_prod_ra_prod_w_price_vo.php");
        return $this->common_get_list($where, $option, "V_prod_ra_prod_w_price_vo", $select_str);
    }

    public function get_ra_prod_list_by_sub_cat($where = array(), $option = array())
    {
        $this->db->select('ra_sku sku, sub_cat_id');
        $this->db->from('v_prod_ra_prod_w_price');
        $this->db->order_by('price asc');
        $this->db->where($where);
        if ($query = $this->db->get())
        {
            $ret = array();
            foreach($query->result(array()) as $row)
            {
                $ret[] = $row;
            }

            return $ret;
        }
        return false;
    }
}
?>