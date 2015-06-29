<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class So_item_profit_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once APPPATH.'libraries/dao/So_item_profit_dao.php';
        $this->set_dao(new So_item_profit_dao());
    }

}
