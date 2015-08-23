<?php
namespace AtomV2\Dao;

abstract class BaseDao
{
    private $rows_limit;

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
            } else {
                if (empty($option["result_type"]) && empty($option["array_list"])) {
                    return (object)$rs;
                } else {
                    return $rs;
                }
            }
        }

        return false;
    }

    public function commonGetList($class_name, $where = [], $option = [], $select = '')
    {
        if ($where) {
            $this->db->where($where);
        }

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

        if ($this->rows_limit != "") {
            $this->db->limit($option["limit"], $option["offset"]);
        }


        if ($select != '') {
            $this->db->select($select, false);
        }

        $rs = [];
        if ($query = $this->db->get()) {
            foreach ($query->result($class_name) as $obj) {
                $rs[] = $obj;
            }
            if ($option["limit"] == 1) {
                return $rs[0];
            } else {
                if ($rs && empty($option["result_type"]) && empty($option["array_list"])) {
                    return (object)$rs;
                } else {
                    return $rs;
                }
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

            $this->setCreate($obj);

            $new_value = false;
            foreach ($class_methods as $fct_name) {
                if (substr($fct_name, 0, 3) == "get") {
                    $rsvalue = call_user_func(array($obj, $fct_name));
                    $rskey = camelcase2underscore(substr($fct_name, 3));
                    if (!in_array($rskey, ['primary_key']) && !in_array($rskey, ['increment_field'])) {
                        $this->db->set($rskey, $rsvalue);
                    }
                }
            }

            if ($this->db->insert($this->getTableName())) {
                if ($ic_field != "" && call_user_func(array($obj, "get" . underscore2camelcase($ic_field))) == 0) {
                    call_user_func(array($obj, "set" . underscore2camelcase($ic_field)), $this->db->insert_id());
                }

                return $obj;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function setCreate(&$obj, $value = [])
    {
        $ts = date("Y-m-d H:i:s");
        $ip = $_SERVER["REMOTE_ADDR"] ? ip2long($_SERVER["REMOTE_ADDR"]) : ip2long("127.0.0.1");
        $id = empty($_SESSION["user"]["id"]) ? "system" : $_SESSION["user"]["id"];
        @call_user_func(array($obj, "setCreateOn"), $ts);
        @call_user_func(array($obj, "setCreateAt"), $ip);
        @call_user_func(array($obj, "setCreateBy"), $id);
        @call_user_func(array($obj, "setModifyOn"), $ts);
        @call_user_func(array($obj, "setModifyAt"), $ip);
        @call_user_func(array($obj, "setModifyBy"), $id);
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

        $this->setModify($obj);

        $primary_key = $obj->getPrimaryKey();
        foreach ($class_methods as $fct_name) {
            if (substr($fct_name, 0, 3) == "get") {
                $rsvalue = call_user_func(array($obj, $fct_name));
                $rskey = camelcase2underscore(substr($fct_name, 3));
                if (in_array($rskey, $primary_key) || in_array($rskey, ['primary_key']) || in_array($rskey, ['increment_field'])) {
                    if (!in_array($rskey, ['primary_key']) && !in_array($rskey, ['increment_field'])) {
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

            return $affected;
        } else {
            return false;
        }
    }

    public function setModify(&$obj, $value = [])
    {
        $ts = date("Y-m-d H:i:s");
        $ip = $_SERVER["REMOTE_ADDR"] ? ip2long($_SERVER["REMOTE_ADDR"]) : ip2long("127.0.0.1");
        $id = empty($_SESSION["user"]["id"]) ? "system" : $_SESSION["user"]["id"];
        @call_user_func(array($obj, "setModifyOn"), $ts);
        @call_user_func(array($obj, "setModifyAt"), $ip);
        @call_user_func(array($obj, "setModifyBy"), $id);
    }

    public function delete(Base_vo $obj)
    {
        $class_methods = get_class_methods($obj);
        foreach ($class_methods as $fct_name) {
            if (substr($fct_name, 0, 3) == "get") {
                $rsvalue = call_user_func(array($obj, $fct_name));
                $rskey = camelcase2underscore(substr($fct_name, 3));
                $this->db->where($rskey, $rsvalue);
            }
        }
        if ($this->db->delete($this->getTableName())) {
            $affected = $this->db->affected_rows();
            return $affected;
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
}
