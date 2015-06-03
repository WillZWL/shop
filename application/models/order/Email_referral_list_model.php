<?php

class Email_referral_list_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('service/email_referral_list_service');
	}

	public function get_all_email_referral_list($where, $option)
	{
		return $this->email_referral_list_service->get_all_email_referral_list($where, $option);
	}

	public function get_csv($where, $option)
	{
		return $this->email_referral_list_service->get_csv($where, $option);
	}
}

/* End of file email_referral_list_model.php */
/* Location: ./system/application/models/email_referral_list_model.php */
?>