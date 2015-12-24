<?php
namespace ESG\Panther\Service;

class GoogleApiRequestService extends BaseService
{
    public function __construct() {
        parent::__construct();
    }
    
    public function getTotalInBatch($batchId) {
        $where = [];
        $where["request_batch_id"] = $batchId;
        $this->getDao("GoogleApiRequest")->db->from("google_api_request");
        $result = $this->getDao("GoogleApiRequest")->commonGetList("", $where, ["num_rows" => 1]);
        return $result;
    }

    public function getNumberOfWarningInBatch($batchId) {
        return $this->getBatchStatus($batchId, "F");
    }

    public function getNumberOfFailInBatch($batchId) {
        return $this->getBatchStatus($batchId, "F");
    }

    public function getNumberOfSuccessInBatch($batchId) {
        return $this->getBatchStatus($batchId, "S");
    }

    public function getBatchStatus($batchId, $status) {
        $where = [];
        $where["request_batch_id"] = $batchId;
        $where["result"] = $status;
        $this->getDao("GoogleApiRequest")->db->from("google_api_request");
        $count = $this->getDao("GoogleApiRequest")->commonGetList("", $where, ["num_rows" => 1]);
        return $count;
    }
}