<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Interface_t3m_score_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once APPPATH."libraries/dao/Interface_t3m_score_dao.php";
        $this->set_dao(new Interface_t3m_score_dao());
        include_once APPPATH."libraries/service/So_service.php";
        $this->so_svc = new So_service();
    }

    public function update_t3m_score($batch_id = "")
    {
        if($batch_id == "")
        {
            return FALSE;
        }

        $list = $this->get_dao()->get_list(array("batch_id"=>$batch_id,"batch_status"=>"R"),array("limit"=>-1));
        echo $this->db->last_query();
        var_dump($list);

        foreach($list as $obj)
        {
            $socc_obj = $this->so_svc->get_socc_dao()->get(array("so_no"=>$obj->get_so_no()));
            $socc_obj->set_t3m_result($obj->get_t3m_score());

            $this->so_svc->get_socc_dao()->update($socc_obj);
        }
    }

}


?>