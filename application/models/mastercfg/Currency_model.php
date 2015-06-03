<?php

class Currency_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library("service/currency_service");
	}

	public function get_name_w_id_key()
	{
		return $this->currency_service->get_name_w_id_key();
	}

	public function update_round_up(&$data)
	{
		foreach ($_POST["round_up"] as $currency_id=>$round_up)
		{
			if (isset($data["currency_list"][$currency_id]) && $data["currency_list"][$currency_id]->get_round_up() != $round_up)
			{
				$data["currency_list"][$currency_id]->set_round_up($round_up);
				if (!$this->currency_service->update($data["currency_list"][$currency_id]))
				{
					$_SESSION["NOTICE"] = "ERROR: ".str_replace(APPPATH, "", __FILE__)."@".__LINE__." ".$this->db->_error_message();
					return FALSE;
				}
			}
		}
		return TRUE;
	}
}

/* End of file currency_model.php */
/* Location: ./system/application/models/currency_model.php */
