<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once "Pmgw.php";

class Pmgw_voucher extends Pmgw
{
	private $v_srv;

	public function __construct()
	{
		parent::__construct();
		include_once(APPPATH."libraries/service/Voucher_service.php");
		$this->set_v_srv(new Voucher_service());
	}

	function set_success_tpl($dto, $acknowledgement, $virtual_only=0, $have_priced_software = 0)
	{
		if($this->get_valid_voucher())
		{
			if ($acknowledgement)
			{
				$dto->set_event_id("acknowledgement");
				$dto->set_tpl_id("acknowledgement");
				$this->soext_add(array("acked"=>1));
			}
			else
			{
				if($this->check_voucher_requirement())
				{
					$this->assign_voucher();
					if($virtual_only)
					{
						if ($have_priced_software)
						{
							$dto->set_event_id('vrt_paid_skype_voucher');
							$dto->set_tpl_id('vrt_paid_skype_voucher');
						}
						else
						{
							$tpl_path = "/var/www/html/valuebasket.com/app/data/template/";

							// This is a bad hardcode way
							if(!is_file($tpl_path."virtual_skype_voucher/virtual_skype_voucher_".$dto->get_lang_id().".html"))
							{
								// default to en if other language file is not found
								$dto->set_lang_id('en');
							}

							$dto->set_event_id("virtual_skype_voucher");
							$dto->set_tpl_id("virtual_skype_voucher");
						}
					}
					else
					{
						if ($have_priced_software)
						{
							$dto->set_event_id("vrt_paid_dlvry_skype_voucher");
							$dto->set_tpl_id("vrt_paid_dlvry_skype_voucher");
						}
						else
						{
							$dto->set_event_id("skype_voucher");
							$dto->set_tpl_id("skype_voucher");
						}
					}
				}
				else
				{
					parent::set_success_tpl($dto, $acknowledgement, $virtual_only, $have_priced_software);
				}
			}
		}
		else
		{
			parent::set_success_tpl($dto, $acknowledgement, $virtual_only, $have_priced_software);
		}

		return $dto;
	}

	public function assign_voucher()
	{
		$so_srv = $this->get_so_srv();
		$so_srv->include_dto("Event_voucher_dto");
		$dto = new Event_voucher_dto();
		$valid_voucher = $this->get_valid_voucher();
		foreach($valid_voucher as $v_obj)
		{
			$ev_dto = clone($dto);
			$ev_dto->set_event_id("assign_voucher");
			$ev_dto->set_so_no($this->so->get_so_no());
			$ev_dto->set_voucher_code($v_obj->get_code());
			$ev_dto->set_voucher_detail_id($v_obj->get_id());
			$this->get_event_srv()->fire_event($ev_dto);
		}
	}

	public function get_valid_voucher()
	{
		$v_srv = $this->get_v_srv();
		return $v_srv->get_voucher_w_detail(array("v.status"=>"1","vd.status"=>"1", "vd.distributed < vd.total_distribution"=>NULL), array("limit"=>"1", "groupby"=>"vd.voucher_id"));
	}

	public function check_voucher_requirement()
	{
		// only orders over $10 will be eligible for voucher code
		if($this->so->get_amount() > 10)
		{
			return TRUE;
		}
		return FALSE;
	}

	public function get_v_srv()
	{
		return $this->v_srv;
	}

	public function set_v_srv($value)
	{
		$this->v_srv = $value;
	}
}

/* End of file pmgw_voucher.php */
/* Location: ./system/application/libraries/service/Pmgw_voucher.php */