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
		$result = $this->getDao("GoogleRequestBatch")->insert($batchObj);
		if ($result === false)
			return false;
		else
			return $batchObj;
	}

    public function setBatchStatus($batchObj, $endTime = "") {
        if (!is_object($batchObj)) {
            $batchObj = $this->getDao("GoogleRequestBatch")->get(["id" => $batchObj]);
        }

        if ($endTime != "")
            $batchObj->setEndTime($endTime);
         
        $total = $this->getService("GoogleApiRequest")->getTotalInBatch($batchObj->getId());
        $success = $this->getService("GoogleApiRequest")->getNumberOfSuccessInBatch($batchObj->getId());

        if ($batchObj->getStatus() != "N")
            $batchObj->setStatus("RP");
        elseif (($total > 0) && ($total == $success))
            $batchObj->setStatus("C");
        elseif ($success > 0)
            $batchObj->setStatus("CE");
        elseif (($total > 0) && ($success == 0))
            $batchObj->setStatus("F");
        else
            $batchObj->setStatus("U");

        $updateResult = $this->getDao("GoogleRequestBatch")->update($batchObj);
        return $updateResult;
    }
}