<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

interface Vb_data_transfer_service_interface
{	

	/*****************************************************************************
	*	process_vb_data, get the VB data to save it in the corresponding tables
	*****************************************************************************/
	public function process_vb_data ($feed);	
}

abstract class Vb_data_transfer_service implements Vb_data_transfer_service_interface
{		
	public $debug = 0;	
		
	public function __construct($debug)
	{		
		$this->debug = $debug;
	}
		
	/*****************************************************************************
	*	start_process, the input would be the xml text from vb and the parameters
	* 	need to send the result data to vb (task_id, task_type)
	******************************************************************************/
	public function start_process($feed)
	{
		$new_feed;
		try
		{
			 $new_feed = $this->process_vb_data($feed);
		}
		catch(exception $e)
		{
			return false;
		}
		//print $new_feed;
		 return $new_feed;
	}
}

/* End of file vb_data_transfer_service.php */
/* Location: ./system/application/libraries/service/vb_data_transfer_service.php */
?>