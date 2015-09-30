<?php  
	namespace ESG\Panther\Service;

interface VbDataTransferServiceInterface
{	

	/*****************************************************************************
	*	processVbData, get the VB data to save it in the corresponding tables
	*****************************************************************************/
	public function processVbData ($feed);	
}

abstract class VbDataTransferService implements VbDataTransferServiceInterface
{		
	public $debug = 0;	
		
	public function __construct($debug)
	{		
		$this->debug = $debug;
	}
		
	/*****************************************************************************
	*	startProcess, the input would be the xml text from vb and the parameters
	* 	need to send the result data to vb (task_id, task_type)
	******************************************************************************/
	public function startProcess($feed)
	{
		$new_feed;
		try
		{
			 $new_feed = $this->processVbData($feed);
		}
		catch(exception $e)
		{
			return false;
		}
		//print $new_feed;
		 return $new_feed;
	}
}

/* End of file VbDataTransferService.php */
/* Location: ./system/application/libraries/service/VbDataTransferService.php */
?>