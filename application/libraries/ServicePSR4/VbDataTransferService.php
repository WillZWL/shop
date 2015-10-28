<?php
namespace ESG\Panther\Service;

interface VbDataTransferServiceInterface
{

    /*****************************************************************************
    *   processVbData, get the VB data to save it in the corresponding tables
    *****************************************************************************/
    public function processVbData ($feed);
}

abstract class VbDataTransferService extends BaseService implements VbDataTransferServiceInterface
{
    public function __construct()
    {
        set_time_limit(600);
        ini_set('memory_limit', '512M');
        parent::__construct();
    }


    /*****************************************************************************
    *   startProcess, the input would be the xml text from vb and the parameters
    *   need to send the result data to vb (task_id, task_type)
    ******************************************************************************/
    public function startProcess($feed)
    {
        $new_feed;
        try {
             $new_feed = $this->processVbData($feed);
        } catch(exception $e) {
            return false;
        }

        return $new_feed;
    }
}
