<?php
namespace ESG\Panther\Service;

class GoogleRequestBatchService extends BaseService
{
    public function __construct() {
    }

	public function getNewBatch($functionName, $batchRemark = null)
	{
		$batchObj = new \GoogleRequestBatchVo;
		$batchObj->setFuncName($functionName);
		$batchObj->setStatus("N");
		$batchObj->setRemark($batchRemark);
		$batchObj->setStartTime(date("Y-m-d H:i:s"));
		$result = $this->getDao('GoogleRequestBatch')->insert($batchObj);
		if ($result === false)
			return false;
		else
			return $batchObj;
	}
}