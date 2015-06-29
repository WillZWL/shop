<?php

include_once "Base_service.php";

class So_compensation_history_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/So_compensation_history_dao.php");
        $this->set_dao(new So_compensation_history_dao());
    }

    public function get_notification_email($compensation_id)
    {
        return $this->get_dao()->get_notification_email($compensation_id);
    }
}

/* End of file so_compensation_history_service.php */
/* Location: ./app/libraries/service/So_compensation_history_service.php */