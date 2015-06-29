<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Base_dao.php';

class Role_dao extends Base_dao {
    private $table_name="role";
    private $vo_class_name="Role_vo";
    private $seq_name="";
    private $seq_mapping_field="";

    public function __construct(){
        parent::__construct();
    }

    public function get_vo_classname(){
        return $this->vo_class_name;
    }

    public function get_table_name(){
        return $this->table_name;
    }

    public function get_seq_name(){
        return $this->seq_name;
    }

    public function get_seq_mapping_field(){
        return $this->seq_mapping_field;
    }
}

/* End of file role_dao.php */
/* Location: ./system/application/libraries/dao/Role_dao.php */