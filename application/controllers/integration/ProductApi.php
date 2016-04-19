<?php
class ProductApi extends PUB_Controller
{

    private $appId="INT0003";

    public function __construct()
    {
        parent::__construct();
    }

    public function update_sku_info($batch_id = '')
    {
        set_time_limit(900);
        if (empty($batch_id)) {
            $res_xml = file_get_contents('php://input', 'r');
            if (!empty($res_xml)) {
                $res = $this->sc['ProductApi']->batchInsertInterfaceSkuInfo($res_xml);
                if ($res['result']) {
                    $batch_id = $res['batch_id'];
                } else {
                    mail('will.zhang@eservicesgroup.com','[Panther] Sync Sku Data to Interface table failed', $res['reason']);
                }
            }
        }
        $this->checkInterfaceSkuInfoData($batch_id);
        $this->updateSkuData($batch_id);
        $this->sendWebStatusChangeEmail($batch_id);
    }

    public function checkInterfaceSkuInfoData($batch_id)
    {
        $this->sc['ProductApi']->checkInterfaceSkuInfoData($batch_id);
    }

    public function updateSkuData($batch_id)
    {
        $this->sc['ProductApi']->updateSkuInfo($batch_id);
    }

    public function pushSkuMappingToCPS()
    {
        $this->sc['ProductApi']->pushSkuMappingToCPS();
    }

    public function sendWebStatusChangeEmail($batch_id)
    {
        $this->sc['ProductApi']->sendWebStatusChangeEmail($batch_id);
    }

    public function getAppId()
    {
        return $this->appId;
    }
}

/* End of file product_api.php */
/* Location: ./system/application/controllers/product_api.php */