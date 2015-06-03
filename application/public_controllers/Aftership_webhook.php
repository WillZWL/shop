<?php
defined('BASEPATH') OR exit('No direct script access allowed');

DEFINE ('ALLOW_REDIRECT_DOMAIN', 1);

class Aftership_webhook extends PUB_Controller
{
	private $lang_id = "en";

	public function __construct()()
	{
		parent::__construct();
		$this->load->model('order/so_model');
		$this->load->library('service/aftership_service');
		$this->load->library('encrypt');
	}

	public function add_tracking()
	{
		$this->aftership_service->add_tracking("98456498asdf");
	}

	public function index()
	{
		//aftership webhook URL   http://dev.valuebasket.com/aftership_webhook/index?encode_text=NmJTRnNlRmZMRUp4ZVRocVpXVVd6dmwxY2NPRy93bWdxTCszUXBJc3FqRT0=
		$password = "db5623-034eff-84a-9f88b155";
		$encode_text = base64_decode($_GET['encode_text']);

		if ($this->encrypt->decode($encode_text) != $password) {
			mail("nero@eservicesgroup.com","aftership webhook", "password validation fail");
		} else {
			//create log
			$respond_body = file_get_contents('php://input');
			$this->create_log($respond_body);

			// if success, then get the post body;

			$aftership_webhook_body = json_decode($respond_body);

			$tracking_no = $aftership_webhook_body->msg->tracking_number;
			$tracking_path = $aftership_webhook_body->msg->unique_token;


			$last_checkpoint_index = sizeof($aftership_webhook_body->msg->checkpoints) - 1;

			$checkpoint_time = $aftership_webhook_body->msg->checkpoints[$last_checkpoint_index]->checkpoint_time;
			$tag = $aftership_webhook_body->msg->checkpoints[$last_checkpoint_index]->tag;


			//remove the T in the checkpoint_time
			$checkpoint_time = str_ireplace("T", " ", $checkpoint_time);
			//var_dump($checkpoint_time);die();

			//convert the text tag into digital and then store in database.
			$aftership_status_mapping = $this->aftership_service->get_aftership_status_mapping();

			$ap_status_number = 0;
			if ( ! ($ap_status_number = array_search(strtolower($tag), array_map("strtolower",$aftership_status_mapping)))) {
				// mail("nero@eservicesgroup.com", "aftership status not found", "$tag can not be converted into digital");
				return FALSE;
			} else {
				if ($sosh_obj = $this->so_model->so_service->get_shipping_info(array("sosh.tracking_no" => $tracking_no))) {
					//var_dump($this->so_model->so_service->get_dao()->db->last_query());die();
					//if all good here, then insert into the
					//if the tag (shippment status) is delivered, then we also need to check more rules to see if we need to send another email to customers

					$sh_no = $sosh_obj->get_sh_no();
					$so_no = substr($sh_no,0,strrpos($sh_no, '-'));
					if ($soext_obj = $this->so_model->so_service->get_soext_dao()->get(array("so_no"=>$so_no))) {
						$soext_obj->set_aftership_status($ap_status_number);
						$soext_obj->set_aftership_checkpoint($checkpoint_time);
						$soext_obj->set_aftership_token($tracking_path);
						$this->so_model->so_service->get_soext_dao()->update($soext_obj);
						if ($ap_status_number == '6') {
							$so_obj = $this->so_model->so_service->get(array("so_no"=>$so_no));
							$this->so_model->so_service->fire_aftership_thank_you_email($so_obj, $sh_no, $ap_status_number);
						}
					} else {
						mail("nero@eservicesgroup.com", "aftership - So order not found in so_extend", "Tracking Number:$tracking_no, shippment status:$tag");
					}
				} else {
					mail("nero@eservicesgroup.com","aftership - Tracking Number doesn't exist - $tracking_no", "Tracking Number:$tracking_no, shippment status:$tag");
				}
			}
		}
	}

	public function create_log($respond_body)
	{
		$file_name = date("Ymd", time()).".txt";
		$file_path = "/var/log/aftership_webhook/".$file_name;
		$respond_body = preg_replace("/\s/",'', $respond_body);
		$respond_body = date("h:i:s Y-m-d", time()).$respond_body."\r";
		if (file_exists($file_path)) {
			file_put_contents($file_path, $respond_body, FILE_APPEND | LOCK_EX);
		} else {
			if ( ! file_put_contents($file_path, $respond_body)) {
				mail("nero@eservicesgroup.com","aftership - cannot create log file", "file path: $file_path");
			}
		}
	}
}
