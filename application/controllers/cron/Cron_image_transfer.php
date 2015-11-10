<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron_image_transfer extends MY_Controller
{
	private $appId="CRN0038";

	function __construct()
    {
        // load controller parent
        parent::__construct();
		
		//price
		$this->load->library('service/vb_product_image_service');
		
		
		// include_once(APPPATH."libraries/dao/schedule_job_dao.php");
		// $this->set_sj_dao(new Schedule_job_dao());			
		$this->load->library('dao/schedule_job_dao');
	}

	public function cron_data_transfer_process()
	{
		//get the initial datetime
		$vars["task_init_datetime"] = $this->set_process_init();
		
		$num_img =$this->vb_product_image_service->transfer_images();
		print $num_img;
		
		//update the last access date in the job
		$this->update_schedule_job_record();
	}	
	
	protected function get_schedule_id()
    {
        return "VB_IMAGE_TRANSFER";
    }
	
	protected function set_process_init()
	{
		$schedule_id = $this->get_schedule_id();
		$sjob_obj = $this->schedule_job_dao->get(array("id" => $schedule_id, "status" => "1"));
		if ($sjob_obj)
		{
			$last_access = $sjob_obj->get_last_access_time();
		}
		else
		{
			$last_access = date("Y-m-d H:i:s");
		}
		
		return $last_access;
	}
	
	protected function update_schedule_job_record()
	{
		$update_time = date("Y-m-d H:i:s");
		$schedule_id = $this->get_schedule_id();
		if($sj_obj = $this->schedule_job_dao->get(array("id"=>$schedule_id)))
		{
			$sj_obj->set_last_access_time($update_time);
			if($this->schedule_job_dao->update($sj_obj) === FALSE)
			{
				return FALSE;
			}
		}
		else
		{
			$sj_obj = $this->schedule_job_dao->get();
			$sj_obj->set_id($schedule_id);
			$sj_obj->set_name("Send data from VB to Atomv2");
			$sj_obj->set_last_access_time($update_time);
			$sj_obj->set_status(1);
			$sj_obj->set_create_on($update_time);
			$sj_obj->set_create_at('localhost');
			$sj_obj->set_create_by('system');
			$sj_obj->set_modify_on($update_time);
			$sj_obj->set_modify_at('localhost');
			$sj_obj->set_modify_by('system');
			if(!$this->schedule_job_dao->insert($sj_obj))
			{
				return FALSE;
			}
		}
	}

	public function getAppId()
    {
        return $this->appId;
    }
}
