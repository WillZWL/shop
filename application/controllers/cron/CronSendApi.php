<?php

class CronSendApi extends MY_Controller
{
    private $appId = 'CRN0044';

    public function __construct()
    {
        parent::__construct();
    }

    public function processGoogleApiRequest() {
        set_time_limit(0);
        do {
            $this->sc['GoogleShopping']->sendBatchRequestToGoogle();
            sleep(300);
        } while (date("H") != "15");
    }

    public function processGoogleApiRequestByBatch($batchId, $reprocess = 0) {
        set_time_limit(0);
        if ($batchId) {
            $this->sc["GoogleShopping"]->processBatchByBatchId($batchId, $reprocess);
            $this->sc["GoogleRequestBatch"]->setBatchStatus($batchId);
        } else {
            print "Please provide a batch ID";
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
