<?php
namespace AtomV2\Dao;

class FaqadminDao extends BaseDao
{
    private $tableName = "faqadmin";
    private $voClassName = "FaqadminVo";

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

    public function getListCnt($where = [], $option = [])
    {
        $this->db->from('language l');
        $this->db->join('faqadmin f', 'f.lang_id = l.lang_id', 'LEFT');

        if (!isset($option["cnt"])) {
            $this->db->select('l.lang_id, f.faq_ver, f.create_at, f.create_on, f.create_by, f.modify_at, f.modify_on, f.modify_by', FALSE);
             if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }

            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($query = $this->db->get_where('', $where, $option["limit"], $option["offset"])) {
                $rs = [];
                foreach ($query->result($this->getVoClassname()) as $obj) {
                    $rs[] = $obj;
                }

                return $rs;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
            echo $this->db->last_query() . " " . $this->db->_error_message();
        }

        return FALSE;
    }
}


