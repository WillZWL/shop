<?php

class Quick_search_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('service/quick_search_service');
        $this->load->library('service/order_notes_service');
        $this->load->library('service/refund_service');
        $this->load->library('service/so_service');
        $this->load->library('service/order_status_history_service');
        $this->load->library('service/client_service');
        $this->load->library('service/delivery_option_service');
        $this->load->model('order/so_model');
        $this->load->library('service/so_priority_score_service');
        $this->load->library('service/so_refund_score_service');
        $this->load->library('service/so_release_order_service');
    }

    public function search_order($where = array(), $option = array())
    {
        return $this->so_service->order_quick_search($where, $option);
        //return $this->so_service->get_dao()->order_quick_search($where,$option);
    }

    public function update_cs_order_query($so_no, $inputValue)
    {
        $so_obj = $this->so_service->get_dao()->get(array("so_no" => $so_no));
        if (isset($inputValue["chasing_order"])) {
            $so_obj->set_cs_customer_query(($so_obj->get_cs_customer_query() & ~1) | $inputValue["chasing_order"]);
        }
        if (isset($inputValue["expect_delivery_date"])) {
            $so_obj->set_expect_delivery_date($inputValue["expect_delivery_date"]);
        }
        return $this->so_service->get_dao()->update($so_obj);
    }

    public function prepareLinkedOrders($so_obj)
    {
        $where = array("so.status >=" => 1);
        $option = array("limit" => -1, "orderby" => "so_no");
        if (($so_obj->get_parent_so_no() != null) && ($so_obj->get_parent_so_no() != "")) {
            $where["so.parent_so_no"] = $so_obj->get_parent_so_no();
            $first_so_no = $so_obj->get_parent_so_no();
        } else {
            $where["so.parent_so_no"] = $so_obj->get_so_no();
            $first_so_no = $so_obj->get_so_no();
        }
        $so_list = $this->so_service->get_dao()->get_so_w_reason($where, $option);
        $first_so = $this->so_service->get_dao()->get_so_w_reason(array("so.so_no" => $first_so_no));

        if ($first_so) {
            if (sizeof((array)$so_list) > 0)
                return array_merge((array)$first_so, (array)$so_list);
            else
                return array();
        }
        /*
                else
                {
                    if (sizeof((array) $so_list) > 0)
                        return array_merge(array(0 => $so_obj), (array) $so_list);
                    else
                        return array();
                }
        */
    }

    public function get_so_with_reason($where = array(), $option = array())
    {
        return $this->so_service->get_dao()->get_so_w_reason($where, $option);
    }

    public function get_list($where = array(), $option = array())
    {
        return $this->selling_platform_service->get_dao()->get_list($where, $option);
    }

    public function get($where = array())
    {
        return $this->so_service->get_dao()->get($where);
    }

    public function update_so($obj)
    {
        return $this->so_service->get_dao()->update($obj);
    }

    public function get_client($where = array())
    {
        return $this->client_service->get_dao()->get($where);
    }

    public function update_client($obj, $where = array())
    {
        return $this->client_service->get_dao()->update($obj, $where);
    }

    public function get_ordered_item($where = array())
    {
        return $this->so_service->get_soi_dao()->get_list($where);
    }

    public function get_order_notes($where = array())
    {
        if (empty($where)) {
            return $this->order_notes_service->get_dao()->get();
        } else {
            return $this->order_notes_service->get_dao()->get_list_w_name($where);
        }
    }

    public function get_country_code()
    {
        return $this->client_service->get_country_dao()->get_list(array(), array("limit" => "-1"));
    }

    public function add_notes($obj)
    {
        return $this->order_notes_service->get_dao()->insert($obj);
    }

    public function get_order_history($where = array())
    {
        return $this->order_status_history_service->get_dao()->get_list_w_username($where);
    }

    public function get_invoice_content($so_no_list = array())
    {
        return $this->so_service->get_invoice_content($so_no_list);
    }

    public function get_refund_history($so_no = "")
    {
        return $this->refund_service->get_refund_for_order_detail($so_no);
    }

    public function get_hold_history($so_no)
    {
        return $this->so_service->get_hold_history($so_no);
    }

    public function get_order_status($so_obj)
    {
        return $this->so_model->get_order_status($so_obj);
    }

    public function get_priority_score($so_no, $biz_type)
    {
        $result = array("score" => 0, "highlight" => 0);

        $so_obj = $this->so_service->get_dao()->get(array("so_no" => $so_no));
        $days = $this->so_service->get_days(strtotime($so_obj->get_order_create_date()), mktime());
        $margin_score = $this->so_priority_score_service->hit_margin_rule($so_no, $biz_type, $days, true);

        if ($margin_score > 0) {
            $result["highlight"] = $margin_score;
        }

        $result["score"] = $this->so_service->get_priority_score($so_no);
        /*
                if ($result["score"] == $margin_score)
                {
        //remove highlight after certain days
                    $result["highlight"] = 0;
                }
        */
        return $result;
    }

    public function get_priority_score_obj($so_no)
    {
        return $this->so_service->get_priority_score_obj($so_no);
    }

    public function get_priority_score_history_list($where = array(), $option = array())
    {
        return $this->so_priority_score_service->get_priority_score_history_list($where, $option);
    }

    public function insert_sops($so_no, $proiority_score)
    {
        return $this->so_priority_score_service->insert_sops($so_no, $proiority_score);
    }

    public function update_sops($so_no, $proiority_score)
    {
        return $this->so_priority_score_service->update_sops($so_no, $proiority_score);
    }

    public function get_sops_history_number($where = array())
    {
        return $this->so_priority_score_service->get_num_rows($where);
    }

    public function get_refund_score_vo($so_no)
    {
        return $this->so_refund_score_service->get_refund_score_vo($so_no);
    }

    public function update_refund_score($so_no, $new_score)
    {
        return $this->so_refund_score_service->update_refund_score($so_no, $new_score);
    }

    public function insert_refund_score($so_no, $new_score)
    {
        return $this->so_refund_score_service->insert_refund_score($so_no, $new_score);
    }

    public function insert_refund_score_history($so_no, $new_score)
    {
        return $this->so_refund_score_service->insert_refund_score_history($so_no, $new_score);
    }

    public function get_refund_score_history_list($where = array(), $option = array())
    {
        return $this->so_refund_score_service->get_refund_score_history_list($where, $option);
    }

    public function get_release_order_history_list($where = array(), $option = array())
    {
        return $this->so_release_order_service->get_release_order_history_list($where, $option);
    }
}
