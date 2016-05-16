<?php
namespace ESG\Panther\Dao;

abstract class BaseDao
{
    abstract public function getVoClassname();
    abstract public function getTableName();

    public function __construct($db = "")
    {
        $CI =& get_instance();

        if ($db) {
            $this->db = $CI->load->database($db, true);
        } else {
            $CI->load->database();
            $this->db =& $CI->db;
        }

        $this->rows_limit = $CI->config->item('rows_limit');
    }

    public function get($where = [], $class_name = "")
    {
        $class_name = ($class_name) ? : $this->getVoClassname();

        if (empty($where)) {
            return new $class_name;
        }

        if ($query = $this->db->get_where($this->getTableName(), $where, 1, 0)) {
            $rs = $query->result($class_name);

            return empty($rs) ? $rs : $rs[0];
        }

        return false;
    }

    public function getList($where = [], $option = [], $class_name = "")
    {
        if (isset($option["orderby"])) {
            $this->db->order_by($option["orderby"]);
        }

        if (isset($option['groupby'])) {
            $this->db->group_by($option['groupby']);
        }

        if (empty($option["limit"])) {
            $option["limit"] = $this->rows_limit;
        } elseif ($option["limit"] == -1) {
            $option["limit"] = "";
        }

        if (!isset($option["offset"])) {
            $option["offset"] = 0;
        }

        if ($query = $this->db->get_where($this->getTableName(), $where, $option["limit"], $option["offset"])) {
            $class_name = ($class_name) ? : $this->getVoClassname();

            $rs = [];
            foreach ($query->result($class_name) as $obj) {
                $rs[] = $obj;
            }

            if ($option["limit"] == 1) {
                return $rs[0];
            }

            return $rs;
        }

        return false;
    }

    public function commonGetList($class_name, $where = [], $option = [], $select = '')
    {
        if ($where) {
            $this->db->where($where);
        }

        if (empty($option["num_rows"])) {
            if (isset($option["orderby"])) {
                $this->db->order_by($option["orderby"]);
            }
            if (isset($option["group_by"])) {
                $this->db->group_by($option["group_by"]);
            }
            if (empty($option["limit"])) {
                $option["limit"] = $this->rows_limit;
            } elseif ($option["limit"] == -1) {
                $option["limit"] = "";
            }

            if (!isset($option["offset"])) {
                $option["offset"] = 0;
            }

            if ($this->rows_limit != "") {
                $this->db->limit($option["limit"], $option["offset"]);
            }

            if ($select != '') {
                $this->db->select($select, false);
            }

            $rs = [];
            if ($query = $this->db->get()) {
                $rs = [];
                foreach ($query->result($class_name) as $obj) {
                    $rs[] = $obj;
                }
                if ($option["limit"] == 1) {
                    return $rs[0];
                }

                return $rs;
            }
        } else {
            $this->db->select('COUNT(*) AS total');
            if ($query = $this->db->get()) {
                return $query->row()->total;
            }
        }

        return false;
    }

    public function getNumRows($where = [])
    {
        $this->db->select('COUNT(*) AS total');
        if ($query = $this->db->get_where($this->getTableName(), $where)) {
            return $query->row()->total;
        }

        return false;
    }

    public function insert($obj = '', $use_increment = true)
    {
        if (! is_object($obj)) {
            return false;
        }

        $class_methods = get_class_methods($obj);
        if (! empty($class_methods)) {
            $ic_field = $obj->getIncrementField();

            if ($ic_field != "" && $use_increment) {
                call_user_func(array($obj, "set" . underscore2camelcase($ic_field)), 0);
            }

            $ts = date("Y-m-d H:i:s");
            $ip = $_SERVER["REMOTE_ADDR"] ? ip2long($_SERVER["REMOTE_ADDR"]) : ip2long("127.0.0.1");
            $id = empty($_SESSION["user"]["id"]) ? "system" : $_SESSION["user"]["id"];
            $new_value = false;
            foreach ($class_methods as $fct_name) {
                $rskey = camelcase2underscore(substr($fct_name, 3));

                if ($fct_name === 'setCreateOn' || $fct_name === 'setModifyOn') {
                    $this->db->set($rskey, $ts);
                    continue;
                }

                if ($fct_name === 'setCreateAt' || $fct_name === 'setModifyAt') {
                    $this->db->set($rskey, $ip);
                    continue;
                }

                if ($fct_name === 'setCreateBy' || $fct_name === 'setModifyBy') {
                    $this->db->set($rskey, $id);
                    continue;
                }

                if (substr($fct_name, 0, 3) == "get") {
                    $rsvalue = call_user_func(array($obj, $fct_name));
                    if (!in_array($rskey, ['primary_key', 'increment_field'])) {
                        $this->db->set($rskey, $rsvalue);
                    }
                }
            }

            if ($this->db->insert($this->getTableName())) {
                if ($ic_field != "" && call_user_func(array($obj, "get" . underscore2camelcase($ic_field))) == 0) {
                    call_user_func(array($obj, "set" . underscore2camelcase($ic_field)), $this->db->insert_id());
                }

                if (($this instanceof HooksInsert) && method_exists($this, 'insertAfterExecute')) {
                    $this->insertAfterExecute($obj);
                }

                return $obj;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function updateTables($data_arr)
    {
        if (isset($data_arr) && $data_arr) {
            foreach ($data_arr as $arr) {
                if (isset($arr['table']) && $arr['table']) {
                    $currDao = 'ESG\Panther\Dao' . "\\" . ucfirst(underscore2camelcase($arr['table'])) . "Dao";
                    $dao = new $currDao;

                    if ($obj = $dao->get($arr['where'])) {
                        if (isset($arr['keyValue']) && $arr['keyValue']) {
                            foreach ($arr['keyValue'] as $rskey => $value) {
                               call_user_func([$obj, "set" . underscore2camelcase($rskey)], $value);
                            }
                        }

                        $dao->update($obj);
                    }
                }
            }
        }
    }

    public function setCommonFields(&$obj, $action = 'insert')
    {

    }

    public function update($obj = null, $where = [])
    {
        if (! is_object($obj)) {
            return false;
        }

        $class_methods = get_class_methods($obj);

        if (! $class_methods) {
            return false;
        }

        $ip = $_SERVER["REMOTE_ADDR"] ? ip2long($_SERVER["REMOTE_ADDR"]) : ip2long("127.0.0.1");
        $id = empty($_SESSION["user"]["id"]) ? "system" : $_SESSION["user"]["id"];

        $primary_key = $obj->getPrimaryKey();
        foreach ($class_methods as $fct_name) {

            if (in_array($fct_name, array('setCreateOn', 'setCreateAt', 'setCreateBy', 'setModifyOn'))) {
                continue;
            }

            if ($fct_name === 'setModifyAt') {
                $this->db->set('modify_at', $ip);
                continue;
            }

            if ($fct_name === 'setModifyBy') {
                $this->db->set('modify_by', $id);
                continue;
            }

            if (substr($fct_name, 0, 3) == "get") {
                $rsvalue = call_user_func(array($obj, $fct_name));
                $rskey = camelcase2underscore(substr($fct_name, 3));
                if (in_array($rskey, $primary_key) || in_array($rskey, ['primary_key']) || in_array($rskey, ['increment_field'])) {
                    if (!in_array($rskey, ['primary_key', 'increment_field'])) {
                        $this->db->where($rskey, $rsvalue);
                    }
                    continue;
                }

                $this->db->set($rskey, $rsvalue);
            }
        }

        if (! empty($where)) {
            $this->db->where($where);
        }

        if ($this->db->update($this->getTableName())) {
            $affected = $this->db->affected_rows();

            if (($this instanceof HooksUpdate) && method_exists($this, 'updateAfterExecute')) {
                $this->updateAfterExecute($obj);
            }

            return ($affected > 0) ? $affected : ture;
        } else {
            return false;
        }
    }

    public function qInsert($data = array())
    {
        if (!empty($data)) {
            if ($this->db->insert($this->getTableName(), $data)) {
                return $this->db->insert_id();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

	public function qUpdate($where = array(), $data = array())
    {
        if (!(empty($where) || empty($data))) {
            $this->db->where($where);
            if ($this->db->update($this->getTableName(), $data)) {
                return $this->db->affected_rows();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function qDelete($where = array())
    {
        if (empty($where)) {
            return false;
        }

        $this->db->where($where);
        if ($this->db->delete($this->getTableName())) {
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }

    public function delete($obj)
    {
        $class_methods = get_class_methods($obj);
        foreach ($class_methods as $fct_name) {
            if (substr($fct_name, 0, 3) == "get") {
                $rsvalue = call_user_func(array($obj, $fct_name));
                $rskey = camelcase2underscore(substr($fct_name, 3));
                if (in_array($rskey, ['primary_key'])) {
                    foreach ($rsvalue as $name) {
                        $new_name = underscore2camelcase('get_'. $name);
                        $new_value = call_user_func(array($obj, $new_name));
                        $this->db->where(camelcase2underscore($name), $new_value);
                    }
                }
            }
        }

        if ($this->db->delete($this->getTableName())) {
            return $this->db->affected_rows();
        } else {
            return false;
        }
    }

    public function getDbTime()
    {
        $sql = "SELECT NOW() AS dbtime";

        if ($query = $this->db->query($sql)) {
            return $query->row()->dbtime;
        }

        return false;
    }

    public function getUserId() {
        $id = "system";
        if (isset($_SESSION["user"]["id"])) {
            $id = $_SESSION["user"]["id"];
        }
        return $id;
    }


    public function seqNextVal()
    {
        // include_once(APPPATH."libraries/Service/ContextConfigService.php");
        $cconfig = new \ESG\Panther\Service\ContextConfigService();
        $this->sequence_table = $cconfig->getDao('Config')->valueOf("sequence_table");

        if ($this->getSeqName() != "")
        {
            $this->db->where('seq_name', $this->getSeqName());
            $query = $this->db->get($this->sequence_table);
            $row = $query->row();
            return $row->value+$row->increment_level;
        }
        else
        {
            return FALSE;
        }
    }

    public function updateSeq($newValue)
    {
        $this->db->where('seq_name', $this->getSeqName());
        if ($this->db->update($this->sequence_table, array('value'=>$newValue)))
        {
            if ($this->db->trans_autocommit)
            {
                $this->db->trans_commit();
            }
            return TRUE;
        }
        else
        {
            if ($this->db->trans_autocommit)
            {
                $this->db->trans_rollback();
                $this->db->trans_commit();
            }
            return FALSE;
        }
    }
}