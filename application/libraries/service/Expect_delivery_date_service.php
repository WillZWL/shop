<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Base_service.php";

class Expect_delivery_date_service extends Base_service
{
	const ORDER_TYPE_ALERT1 = "ALERT1";
	const ORDER_TYPE_ALERT2 = "ALERT2";

	public $so_dao;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH . "libraries/dao/So_dao.php");
		$this->set_so_dao(new So_dao());
	}

	public function send_report($type)
	{
		if ($type == self::ORDER_TYPE_ALERT1)
		{
			$this->send_not_chasing_order_report();
		}
		else if ($type == self::ORDER_TYPE_ALERT2)
		{
			$this->send_not_chasing_order_report_alert2();
		}
	}

	public function send_not_chasing_order_report()
	{
		$where = array();
		$where["so.status"] = 3;
		$where["so.biz_type"] = "ONLINE";
		$where["(so.cs_customer_query & 1) = 0"] = null;
		$where["so.refund_status"] = 0;
		$where["so.hold_status"] = 0;
		$where["DATE(so.order_create_date) = DATE(SUBDATE(now(), SUBSTRING_INDEX(so.expect_del_days,' ',-1)) + 2)"] = null;

		$option = array("group_by" => "so.so_no");

		$orderList = $this->get_so_dao()->send_not_chasing_order_report($where, $option);

		$csv = $this->gen_csv($orderList);

		$title = "Project Expect Delivery Date alert #1" ;
		$message = "Project Expect Delivery Date alert #1";
		$filename = 'expect_delivery_date_not_chasing_' . date('Ymd') . '.csv';

		$this->_email_report($csv, $title, $message, $filename, "projectexpect_delivery_date-alert1@valuebasket.com", "projectexpect_delivery_date-alert1@supportsave.com");
	}

	public function send_not_chasing_order_report_alert2()
	{
		$where = array();
		$where["so.status"] = 3;
		$where["so.biz_type"] = "ONLINE";
		$where["(so.cs_customer_query & 1) = 0"] = null;
		$where["so.refund_status"] = 0;
		$where["so.hold_status"] = 0;
		$where["SUBDATE(so.expect_delivery_date, '1 DAY') ="] = date("Y-m-d");

		$option = array("group_by" => "so.so_no");

		$orderList = $this->get_so_dao()->send_not_chasing_order_report_alert2($where, $option);
//		print $this->get_so_dao()->db->last_query();

		$csv = $this->gen_csv($orderList);

		$title = "Project Expect Delivery Date alert #2" ;
		$message = "Project Expect Delivery Date alert #2";
		$filename = 'expect_delivery_date_not_chasing_2_' . date('Ymd') . '.csv';

		$this->_email_report($csv, $title, $message, $filename, "projectexpect_delivery_date-alert1@valuebasket.com", "projectexpect_delivery_date-alert1@supportsave.com");
	}

	private function _email_report($csv = "", $title = "", $message = "", $filename, $email1, $email2 = null)
	{
		include_once(BASEPATH . "plugins/phpmailer/phpmailer_pi.php");
		$phpmail = new PHPMailer();
		$phpmail->IsSMTP();
		$phpmail->From = "Admin <admin@valuebasket.com>";

		$phpmail->AddAddress($email1);

		if ($email2)
			$phpmail->AddAddress($email2);

		$phpmail->Subject = $title;
		$phpmail->IsHTML(false);
		$phpmail->Body = $message;
		$phpmail->AddStringAttachment($csv, $filename, 'base64', 'text/csv');

		$result = $phpmail->Send();
	}

	private function gen_csv($data=array())
	{
		$csv = "";
		if(!empty($data))
		{
			foreach ($data as $key => $value)
			{
				if($key==0)
				{
					foreach ($value as $label => $v)
					{
						$csv .= "$label,";
					}

					$csv .= "\n";
				}

				$csv .= implode(",", $value);
				$csv .= "\n";
			}
		}
		return $csv;
	}

	public function get_so_dao()
	{
		return $this->so_dao;
	}

	public function set_so_dao($value)
	{
		$this->so_dao = $value;
	}
}

/* End of file Expect_delivery_date_service.php */
/* Location: ./system/application/libraries/service/Expect_delivery_date_service.php */
