<?php

include_once "Base_service.php";

class So_refund_score_service extends Base_service
{
    public function __construct()
    {
        parent::__construct();
        include_once(APPPATH . "libraries/dao/So_refund_score_dao.php");
        $this->set_dao(new So_refund_score_dao());
        include_once(APPPATH . "libraries/dao/So_refund_score_history_dao.php");
        $this->set_sors_history_dao(new So_refund_score_history_dao());
        include_once(APPPATH . "libraries/dao/So_payment_status_dao.php");
        $this->set_sops_dao(new So_payment_status_dao());
        include_once(APPPATH . "libraries/dao/So_dao.php");
        $this->set_so_dao(new So_dao());
    }

    public function set_sors_history_dao($dao)
    {
        $this->sors_history_dao = $dao;
        return $dao;
    }

    public function set_sops_dao($dao)
    {
        $this->sops_dao = $dao;
        return $dao;
    }
	
	public function set_so_dao($dao)
    {
        $this->so_dao = $dao;
        return $dao;
    }

    public function set_dao($dao)
    {
        $this->so_refund_score_dao = $dao;
        return $dao;
    }

    public function insert_initial_refund_score($orderid)
    {
        $payment_gateway_id = strtolower($this->get_so_payment_gateway_id($orderid));
        $payment_gateway_list = array("paypal", "w_bank_transfer");

        $so_obj = $this->get_so_dao()->get(array("so_no" => $orderid));
        $delivery_status = $so_obj->get_status();
        $platform_id = $so_obj->get_platform_id();
        $platform_id_list = array("QOO10SG", "TMNZ");

        if (in_array($payment_gateway_id, $payment_gateway_list) || in_array($platform_id, $platform_id_list) || $delivery_status == '6') {
            $refund_score = 2;
        } else {
            $refund_score = 0;
        }
		
			print "1";
		
        if (!$this->get_refund_score_vo($orderid)) {
			print "2";
            $this->insert_refund_score($orderid, $refund_score);		
			$this->insert_refund_score_history($orderid, $refund_score);
		}
    }

    public function get_so_payment_gateway_id($so_no)
    {
        if ($sops_vo = $this->get_sops_dao()->get(array("so_no" => $so_no))) {
            return $sops_vo->get_payment_gateway_id();
        } else {
            return FALSE;
        }
    }
	
	public function get_dao()
    {
        return $this->so_refund_score_dao;
    }

    public function get_sops_dao()
    {
        return $this->sops_dao;
    }

    public function get_so_dao()
    {
        return $this->so_dao;
    }

    public function get_refund_score_vo($so_no)
    {
        return $this->get_dao()->get(array("so_no" => $so_no));
    }

    public function insert_refund_score($so_no, $new_score)
    {
		print $so_no;
        $new_sorf_vo = $this->get_dao()->get();		
		
		print "4";
		print $so_no;
        $new_sorf_vo->set_so_no($so_no);
		
		print $new_score;
        $new_sorf_vo->set_score($new_score);
        return $this->get_dao()->insert($new_sorf_vo);
    }

    public function insert_refund_score_history($so_no, $new_score)
    {
        $new_sorf_history_vo = $this->get_sors_history_dao()->get();
        $new_sorf_history_vo->set_so_no($so_no);
        $new_sorf_history_vo->set_score($new_score);
        return $this->get_sors_history_dao()->insert($new_sorf_history_vo);
    }

    public function get_sors_history_dao()
    {
        return $this->sors_history_dao;
    }

    public function update_refund_score($so_no, $new_score)
    {
        $sorf_vo = $this->get_dao()->get(array('so_no' => $so_no));
        $sorf_vo->set_score($new_score);
        return $this->get_dao()->update($sorf_vo);
    }

    public function get_refund_score_history_list($where = array(), $option = array())
    {
        return $this->get_sors_history_dao()->get_list($where, $option);
    }
}


