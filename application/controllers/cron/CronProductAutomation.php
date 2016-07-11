<?php
class CronProductAutomation extends MY_Controller
{
    private $appId="CRN0033";

    public function __construct()
    {
        parent::__construct();
    }

    public function updateProductQty()
    {
        $this->sc['ProductAutomation']->updateProductQty();
    }

    public function sendAutoChangeEmail($batch_id = '')
    {
        $this->sc['ProductAutomation']->sendAutoChangeEmail(array('batch_id' => $batch_id));
    }


    public function updateProductWarranty($product_warranty_type = '3', $platform_id = 'WEBGB')
    {
        $product_list = $this->sc['Product']->getDao('Product')->getList(array('product_warranty_type' => $product_warranty_type), array('limit' => '-1'));
        foreach ($product_list as $product_obj) {
            $this->sc['ProductWarranty']->autoCreateProductWarranty($product_obj, $platform_id);
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }
}
