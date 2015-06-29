<?php

include_once 'Base_dao.php';

class Promotion_text_dao extends Base_dao
{
    private $table_name="promotion_text";
    private $vo_classname="Promotion_text_vo";
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

    public function get_promo_text($platform_type, $lang_id, $platform_id, $sku)
    {
        $sql  = "
                SELECT *
                FROM promotion_text pt
                WHERE pt.platform_type = ?
                ORDER BY pt.promo_text IS NOT NULL DESC, IFNULL((pt.sku = '$sku' AND pt.platform_id = '$platform_id' AND pt.lang_id = '$lang_id'),0) DESC,
                    IFNULL((pt.sku IS NULL AND pt.platform_id = '$platform_id' AND pt.lang_id = '$lang_id'),0) DESC,
                    IFNULL((pt.sku IS NULL AND pt.platform_id is null AND pt.lang_id = '$lang_id'),0) DESC
                LIMIT 1;
                ";

        if ($query = $this->db->query($sql, array($platform_type)))
        {
            return $query->row(0, '');
        }
        else
        {
            return FALSE;
        }
    }
}

/* End of file promotion_text_dao.php */
/* Location: ./app/libraries/dao/Promotion_text_dao.php */