<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Graphic_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Graphic_dao.php");
        $this->set_dao(new Graphic_dao());
    }

    public function seq_next_val()
    {
        return $this->get_dao()->seq_next_val();
    }

    public function update_seq($value)
    {
        return $this->get_dao()->update_seq($value);
    }
}

/* End of file Display_banner_service.php */
/* Location: ./app/libraries/service/Display_banner_service.php */