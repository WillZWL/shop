<?php
namespace ESG\Panther\Service;

interface VbDataTransferServiceInterface
{

	/*****************************************************************************
	*	processVbData, get the VB data to save it in the corresponding tables
	*****************************************************************************/
	public function processVbData ($feed);

}

abstract class VbDataTransferService extends BaseService implements VbDataTransferServiceInterface
{
	/*****************************************************************************
	*	startProcess, the input would be the xml text from vb and the parameters
	* 	need to send the result data to vb (task_id, task_type)
	******************************************************************************/
	public function startProcess(&$feed)
	{
		$new_feed;
        try {
             $new_feed = $this->processVbData($feed);
        } catch(exception $e) {
            return false;
        }
        unset($feed);
        return $new_feed;
	}

	public function replaceSpecialChars($replaced_data)
	{
		$original_data = "";

		$original_data = str_replace('&amp;', '&', $replaced_data);
		$original_data = str_replace('&gt;', '>', $original_data);
		$original_data = str_replace('&lt;', '<', $original_data);

		return $original_data;
	}
}