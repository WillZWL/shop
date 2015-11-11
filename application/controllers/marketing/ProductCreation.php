<?php
class ProductCreation extends MY_Controller
{
    private $app_id = "MKT0003";
    private $data;

    public function __construct()
    {
        parent::__construct();

        $this->data['ProductVo'] = new \ProductVo();
        $this->data['SupplierProdVo'] = new \SupplierProdVo();
        $this->data['PCCVo'] = new \ProductCustomClassificationVo();
        $this->data['SkuMappingVo'] = new \SkuMappingVo();
        // $this->data['ProductSpecVo'] = new \ProductSpecVo();
        $this->data['PSDVo'] = new \ProductSpecDetailsVo();
        $this->data['ProductWarrantyVo'] = new \ProductWarrantyVo();
        $this->data['ProudctKeywordVo'] = new \ProductKeywordVo();
        $this->data['ProductContentVo'] = new \ProductContentVo();
        $this->data['ProductContentVo'] = new \ProductContentVo();
        $this->data['ProductImage'] = new \ProductImageVo();
        $this->data['ProductContentExtendVo'] = new \ProductContentExtendVo();
    }

    public function createProductByXML($data = '')
    {
        if ($data) {
            // $this->data = $data;

            $this->sc['ProductCreation']->process($this->data);
        }

    }

    public function process()
    {
        $this->sc['ProductCreation']->process();
    }

    public function getAppId()
    {
        return $this->app_id;
    }
}
