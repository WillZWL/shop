<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Quick_search_service extends Base_service
{
    private $pmgw_card_dao;

    public function __construct()
    {
        parent::__construct();
        $CI =& get_instance();
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_dao(new So_dao());
        include_once(APPPATH . "libraries/dao/Pmgw_card_dao.php");
        $this->set_pmgw_card_dao(new Pmgw_card_dao());
        include_once(APPPATH . "libraries/dao/Order_notes_dao.php");
        $this->set_order_notes_dao(new Order_notes_dao());
    }

    public function set_order_notes_dao(Base_dao $dao)
    {
        $this->order_notes_dao = $dao;
    }

    public function get_pmgw_card_dao()
    {
        return $this->pmgw_card_dao;
    }

    public function set_pmgw_card_dao(Base_dao $dao)
    {
        $this->pmgw_card_dao = $dao;
    }

    public function get_order_note($where = array(), $option = array())
    {
        return $this->get_order_notes_dao()->get_list($where, $option);
    }

    public function get_order_notes_dao()
    {
        return $this->order_notes_dao;
    }
}

?>