<?php
namespace ESG\Panther\Service;

class ComplementaryAccService extends BaseService
{
    public $accessoryCatidArr;

    public function __construct()
    {
        parent::__construct();
        // sets the category id of complementary accessory
        $this->setAccessoryCatidArr();
    }


    public function calculateComplementaryAccCost(\PriceWithCostDto $dto)
    {
        $sub_cost = 0;

        $where["pca.dest_country_id"] = $dto->getPlatformCountryId();
        $where["pca.mainprod_sku"] = $dto->getSku();
        $where["pca.status"] = 1;

        if ($mapped_ca_list = $this->getDao('ProductComplementaryAcc')->getMappedAccListWithName($where)) {
            $sku_arr = [];
            foreach ($mapped_ca_list as $caobj) {
                $sku_arr[] = $caobj->getAccessorySku();
            }
            $sku_list = "'". implode("','", $sku_arr) . "'";
            if ($cadto = $this->getDao('Price')->getPriceWithCost(["p.sku in ({$sku_list})" => null, 'pbv.selling_platform_id' => $dto->getPlatformId() ], ['sum_complementary_cost'=>1, 'limit'=>1 ]) ) {
                $sub_cost = $cadto->getSupplierCost();
            }
        }

        $dto->setComplementaryAccCost($sub_cost);
    }




    public function setAccessoryCatidArr()
    {
        $this->accessoryCatidArr = $this->getAccessoryCatidArr();
    }

    public function getAccessoryCatidArr()
    {
        $accessoryCatidArr = $this->getDao('ProductComplementaryAcc')->getAccessoryCatidArr();
        return $accessoryCatidArr;
    }

    public function getMappedAccListWithName($where = [], $option = [], $active = true)
    {
        return $this->getDao('ProductComplementaryAcc')->getMappedAccListWithName($where, $option, $active);
    }

    public function checkCat($sku = "", $is_ca = true)
    {
        $ret = $this->getDao('ProductComplementaryAcc')->checkCat($sku, $is_ca);
        return $ret;
    }
}