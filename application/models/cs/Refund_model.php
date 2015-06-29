<?php

class Refund_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/refund_service');
        $this->load->library('service/so_service');
    }

    public function get_reason($where = array())
    {
        if (empty($where)) {
            return $this->refund_service->get_reason_dao()->get();
        }

        return $this->refund_service->get_reason_dao()->get($where);
    }

    public function get_reason_list($where, $option)
    {
        return array("reason_list" => $this->refund_service->get_reason_dao()->get_list($where, $option),
            "cnt" => $this->refund_service->get_reason_dao()->get_num_rows($where));
    }

    public function add_reason($obj)
    {
        return $this->refund_service->get_reason_dao()->insert($obj);
    }

    public function update_reason($obj)
    {
        return $this->refund_service->get_reason_dao()->update($obj);
    }

    public function __autoload_reason()
    {
        include_once APPPATH . "libraries/vo/refund_reason_vo.php";
    }

    public function get_order_list($where = array(), $option = array())
    {
        return array("list" => $this->so_service->get_refundable_list($where, $option),
            "total" => $this->so_service->get_refundable_list($where, array("num_row" => 1, "create" => $option["create"])));
    }

    public function get_history_list($where)
    {
        return $this->refund_service->get_history_dao()->get_history_list($where);
    }

    public function get_item_list($where = array())
    {
        return $this->so_service->get_soid_dao()->get_list_w_prodname($where, array("sortby" => "line_no ASC"));
    }

    public function is_cod_order($so_no)
    {
        return $this->so_service->is_cod_order($so_no);
    }

    public function get_so($where = array())
    {
        return $this->so_service->get_dao()->get($where);
    }

    public function get_so_list($where = array())
    {
        return $this->so_service->get_dao()->get_list($where);
    }

    public function update_so($obj)
    {
        return $this->so_service->get_dao()->update($obj);
    }

    public function get_refund_so_list($where = array(), $option = array())
    {
        return array("list" => $this->refund_service->get_dao()->get_refund_list($where, $option),
            "total" => $this->refund_service->get_dao()->get_refund_list($where, array("num_row" => 1)));
    }

    public function get_refund($where = array())
    {
        if (empty($where)) {
            return $this->refund_service->get_dao()->get();
        } else {
            return $this->refund_service->get_dao()->get($where);
        }
    }

    public function get_refund_item($where = array())
    {
        if (empty($where)) {
            return $this->refund_service->get_ritem_dao()->get();
        } else {
            return $this->refund_service->get_ritem_dao()->get($where);
        }
    }

    public function get_refund_history($where = array())
    {
        if (empty($where)) {
            return $this->refund_service->get_history_dao()->get();
        } else {
            return $this->refund_service->get_history_dao()->get($where);
        }
    }

    public function get_refund_item_list($where = array(), $option = array())
    {
        return $this->refund_service->get_ritem_dao()->get_list_w_name($where, $option);
    }

    public function insert_refund_item($obj)
    {
        return $this->refund_service->get_ritem_dao()->insert($obj);
    }

    public function update_refund_item($obj)
    {
        return $this->refund_service->get_ritem_dao()->update($obj);
    }

    public function insert_refund_history($obj)
    {
        return $this->refund_service->get_history_dao()->insert($obj);
    }

    public function update_refund_history($obj)
    {
        return $this->refund_service->get_history_dao()->update($obj);
    }

    public function insert_refund($obj)
    {
        return $this->refund_service->get_dao()->insert($obj);
    }

    public function update_refund($obj)
    {
        return $this->refund_service->get_dao()->update($obj);
    }

    public function check_action($refundid = "", $action = "", $auto_refund = false)
    {
        return $this->refund_service->check_action($refundid, $action, $auto_refund);
    }

    public function check_complete($refundid = "")
    {
        return $this->refund_service->get_dao()->check_complete($refundid);
    }

    public function fire_email($rid, $status, $result)
    {
        return $this->refund_service->fire_email($rid, $status, $result);
    }

    public function _trans_start()
    {
        $this->refund_service->get_dao()->trans_start();
    }

    public function _trans_complete()
    {
        $this->refund_service->get_dao()->trans_complete();
    }

}

?>