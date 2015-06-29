<?php
class On_hold_admin_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/so_service');
        $this->load->library('service/refund_service');
        $this->load->library('service/client_service');
    }

    public function get_list($dao="dao", $where=array(), $option=array())
    {
        $dao = "get_".$dao;
        return $this->so_service->$dao()->get_list($where, $option);
    }

    public function get_num_rows($dao="dao", $where=array())
    {
        $dao = "get_".$dao;
        return $this->so_service->$dao()->get_num_rows($where);
    }

    public function get($dao="dao", $where=array())
    {
        $dao = "get_".$dao;
        return $this->so_service->$dao()->get($where);
    }

    public function update($dao="dao", $obj)
    {
        $dao = "get_".$dao;
        return $this->so_service->$dao()->update($obj);
    }

    public function add($dao="dao", $obj)
    {
        $dao = "get_".$dao;
        return $this->so_service->$dao()->insert($obj);
    }

    public function include_vo($dao)
    {
        $dao = "get_".$dao;
        return $this->so_service->$dao()->include_vo();
    }

    public function _trans_start($dao)
    {
        $this->so_service->$dao()->trans_start();
    }

    public function _trans_complete($dao)
    {
        $this->so_service->$dao()->trans_complete();
    }

    public function add_refund_item($obj)
    {
        $this->refund_service->get_ritem_dao()->insert($obj);
    }

    public function add_refund($obj)
    {
        $this->refund_service->get_dao()->insert($obj);
    }

    public function add_refund_history($obj)
    {
        $this->refund_service->get_history_dao()->insert($obj);
    }

    public function create_refund($so_no="")
    {
        return $so_no==""?FALSE:$this->refund_service->create_refund($so_no);
    }

    public function get_event_dto()
    {
        return $this->refund_service->get_event_dto();
    }

    public function get_client($where = array())
    {
        return $this->client_service->get_dao()->get($where);
    }

    public function check_if_packed($so_no = "")
    {
        return $this->so_service->check_if_packed($so_no);
    }

    public function fire_log_email_event($so_no="", $template="", $option="")
    {
        return $this->so_service->fire_log_email_event($so_no, $template, $option);
    }

    public function fire_cs2log_email($so_no="",$reason="",$user_info=array())
    {
        $this->so_service->fire_cs2log_email($so_no,$reason,$user_info);
    }
}

/* End of file credit_check_model.php */
/* Location: ./system/application/models/credit_check_model.php */
