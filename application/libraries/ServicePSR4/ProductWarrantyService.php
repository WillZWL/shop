<?php
namespace ESG\Panther\Service;

class ProductWarrantyService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    protected $warranty_arr = [1 => 'accessories', 2 => 'waterproof', 3 => 'main_items', 4 => 'action_camera', 5 => 'drones', 6 => 'refurbished', 7 => 'no_warranty'];

    public function autoCreateProductWarranty($product_obj)
    {
        $sku = $product_obj->getSku();
        $product_warranty_type = $product_obj->getProductWarrantyType();

        $warranty_data = $this->getPlatformWarranty();
        $platform_lsit = $this->getDao('SellingPlatform')->getList(array('status' => 1), array('limit' => -1));
        $warranty_arr = $this->warranty_arr;

        if ($product_warranty_type > 0) {
            $product_warranty = $warranty_arr[$product_warranty_type];

            foreach ($platform_lsit as $platform_obj) {

                $platform_id = $platform_obj->getSellingPlatformId();
                $warranty_in_month = $warranty_data[$product_warranty][$platform_id];

                $product_warranty_obj = $this->getDao('ProductWarranty')->get(array('sku' => $sku, 'platform_id' => $platform_id));
                if ($product_warranty_obj) {
                    if ($warranty_in_month != $product_warranty_obj->getWarrantyInMonth()) {
                        $product_warranty_obj->setWarrantyInMonth($warranty_in_month);
                        $this->getDao('ProductWarranty')->update($product_warranty_obj);
                    }
                } else {
                    $product_warranty_vo = $this->getDao('ProductWarranty')->get();
                    $product_warranty_obj = clone $product_warranty_vo;
                    $product_warranty_obj->setSku($sku);
                    $product_warranty_obj->setPlatformId($platform_id);
                    $product_warranty_obj->setWarrantyInMonth($warranty_in_month);

                    $this->getDao('ProductWarranty')->insert($product_warranty_obj);
                }
            }
        }
    }

    private function getPlatformWarranty()
    {
        $warranty_list = $this->getDao('PlatformWarranty')->getList();
        $data = array();
        foreach ($warranty_list as $warranty) {
            $platform_id = $warranty->getPlatformId();
            $data['accessories'][$platform_id] = $warranty->getAccessories();
            $data['waterproof'][$platform_id] =$warranty->getWaterproof();
            $data['main_items'][$platform_id] =$warranty->getMainItems();
            $data['action_camera'][$platform_id] =$warranty->getActionCamera();
            $data['drones'][$platform_id] =$warranty->getDrones();
            $data['refurbished'][$platform_id] =$warranty->getRefurbished();
            $data['no_warranty'][$platform_id] =$warranty->getNoWarranty();
        }
        return $data;
    }
}