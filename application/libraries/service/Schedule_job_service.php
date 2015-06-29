<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Schedule_job_service extends Base_service {

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Schedule_job_dao.php");
        $this->set_dao(new Schedule_job_dao());
    }

    public function get_last_process_time($id)
    {
        if($obj = $this->get_dao()->get(array("id"=>$id, "status"=>1)))
        {
            return $obj->get_last_access_time();
        }
    }

    public function update_last_process_time($id, $update_time)
    {
        if($obj = $this->get_dao()->get(array("id"=>$id, "status"=>1)))
        {
            $obj->set_last_access_time($update_time);
            return $this->get_dao()->update($obj);
        }
    }
}

/* End of file schedule_job_service.php */
/* Location: ./system/application/libraries/service/Schedule_job_service.php */