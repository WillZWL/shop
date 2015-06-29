<?php

include_once "Base_service.php";
class Competitor_service extends Base_service
{

    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH."libraries/dao/Competitor_dao.php");
        $this->set_dao(new Competitor_dao());
    }

    public function get($where=array())
    {
        return $this->get_dao()->get($where);
    }

    public function insert($obj)
    {
        return $this->get_dao()->insert($obj);
    }

    public function update($obj)
    {
        return $this->get_dao()->update($obj);
    }

    public function get_list($where=array(), $option=array())
    {
        return $this->get_dao()->get_list($where, $option);
    }


}
