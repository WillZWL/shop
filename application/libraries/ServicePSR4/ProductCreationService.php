<?php
namespace ESG\Panther\Service;

class ProductCreationService extends BaseService
{
    public function saveByXML($xml)
    {
    }

    public function create()
    {
        $sku = $this->getDao()->db->query("SELECT next_value('sku') as sku")->row('sku');
        $prod_grp_cd = $this->getDao()->db->query("SELECT next_value('prod_grp_cd') as prod_grp_cd")->row('prod_grp_cd');

        $productVo->setSku($sku);
        $productVo->setProdGrgCd($prod_grp_cd);
        $supplierProdVo->setSku($sku);
        $pccVo->setSku($sku);

        $this->getDao->tran_start();
        $result_1 = $this->getProductDao()->insert($productVo);
        $result_2 = $this->getSupplierProdDao()->insert($supplierProdVo);
        $result_3 = $this->getProductCustomClassificationDao()->insert($pccVo);
        if ($result_1 && $result_2 && $result_3) {
            $this->getDao()->tran_complete();
        } else {
            $this->getDao()->tran_rollback();
        }

        return $sku;
    }

    public function createContent(MediaInterface $mediaObj = null)
    {
        $this->getDao()->tran_start();
        $sku = $this->create();

        if ($skuMappingVo->getExtSku()) {
            $skuMappingVo->setSku($sku);
            $this->getDao('skuMapping')->insert($skuMappingVo);
        }


        $this->getDao('productSpec')->insert($productSpecVo);

        $productSpecDetailsVo->setSku($sku);
        $this->getDao('productSpecDetails')->insert($productSpecDetailsVo);

        if ($mediaObj) {
            $mediaObj->create();
        }

        $productWarrantyVo->setSku($sku);
        $this->getDao('productWarranty')->insert($productWarrantyVo);
        $obj = $this->getProductCustomClassificationDao()->get($where);
        $this->getProductCustomClassificationDao()->update();
        $this->getProductKeywordDao()->delete($where);
        $this->getProductKeywordDao()->insert($where);
        $this->getProductContentDao()->delete($where);
        $this->getProductContentDao()->insert($where);
        $this->getProductContentExtendDao()->delete($where);
        $this->getProductContentExtendDao()->insert($where);
        $this->getProductFeedDao()->insert();

        $this->getDao()->tran_complete();
    }
}
