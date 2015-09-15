<?php
namespace ESG\Panther\Service;

class ProductCreationService extends BaseService
{
    private $data;

    public function process($data = '')
    {
        if (!isset($data['SkuMappingVo'])) {
            return false;
        }

        $extSku = $data['SkuMappingVo']->getExtSku();
        // $extSku = '20309-AA-NA';

        if ($extSku) {
            $skuMappingObj = $this->getDao('SkuMapping')->get(['ext_sku' => $extSku]);
            if ($skuMappingObj) {
                $this->updateProduct($data);
            } else {
                $this->insertNewProduct($data, true);
            }
        } else {
            $this->insertNewProduct($data, false);
        }
    }

    public function insertNewProduct($data, $has_ext_sku = true)
    {
        $sku = $this->getDao('Product')->getNewSku();
        $skuGroup = $this->getDao('Product')->getNewProductGroup();

        $p_vo = new \ProductVo();
        set_value($p_vo, $data['ProductVo']);
        $p_vo->setSku($sku);
        $p_vo->setProdGrpCd($skuGroup);

        $sp_vo = new \SupplierProdVo();
        set_value($sp_vo, $data['SupplierProdVo']);
        $sp_vo->setProdSku($sku);

        $pcc_vo = new \ProductCustomClassificationVo();
        set_value($pcc_vo, $data['PCCVo']);
        $pcc_vo->setSku($sku);

        if ($has_ext_sku) {
            $sm_vo = new \SkuMappingVo();
            set_value($sm_vo, $data['SkuMappingVo']);
            $sm_vo->setSku($sku);
        }

        // $ps_vo = new \ProductSpecVo();
        // set_value($ps_vo, $data['ProductSpecVo']);

        $psd_vo = new \ProductSpecDetailsVo();
        set_value($psd_vo, $data['ProductSpecDetailsVo']);
        $psd_vo->setProdSku($sku);

        $pw_vo = new \ProductWarrantyVo();
        set_value($pw_vo, $data['ProductWarrantyVo']);
        $pw_vo->setSku($sku);

        $pk_vo = new \ProductKeywordVo();
        set_value($pk_vo, $data['ProductKeywordVo']);
        $pk_vo->setSku($sku);

        $pc_vo = new \ProductContentVo();
        set_value($pc_vo, $data['ProductContentVo']);
        $pc_vo->setProdSku($sku);

        $pi_vo = new \ProductImageVo();
        set_value($pi_vo, $data['ProductImageVo']);
        $pi_vo->setSku($sku);

        $pce_vo = new \ProductContentExtendVo();
        set_value($pce_vo, $data['ProductContentExtendVo']);
        $pce_vo->setProdSku($sku);

        $this->getDao('Product')->db->trans_start();
        $this->getDao('Product')->insert($p_vo);
        $this->getDao('SupplierProd')->insert($sp_vo);
        $this->getDao('ProductCustomClassification')->insert($pcc_vo);
        if ($has_ext_sku) {
            $this->getDao('SkuMpaping')->insert($sm_vo);
        }
        $this->getDao('ProductSpecDetails')->insert($psd_vo);
        $this->getDao('ProductWarranty')->insert($pw_vo);
        $this->getDao('ProductKeyword')->insert($pk_vo);
        $this->getDao('ProductContent')->insert($pc_vo);
        $this->getDao('ProductImage')->insert($pi_vo);
        $this->getDao('ProductContentExtend')->insert($pce_vo);
        $this->getDao('Product')->db->trans_complete();
    }

    public function updateProduct($data)
    {
        $skuMappingObj = $this->getDao('SkuMpaping')->get(['ext_sku' => $extSku]);
        $sku = $skuMappingObj->getSku();

        $p_vo = new \ProductVo();
        $p_vo_old = $this->getDao('Product')->get(['sku' => $sku]);
        set_value($p_vo, $data['ProductVo']);
        $p_vo->setId($p_vo_old->getId());
        $p_vo->setSku($sku);
        $p_vo->setProdGrgCd($skuGroup);

        $sp_vo = new \SupplierProdVo();
        $sp_vo_old = $this->getDao('SupplierProd')->get(['prod_sku' => $sku]);
        set_value($sp_vo, $data['SupplierProdVo']);
        $sp_vo->setId($sp_vo_old->getId());
        $sp_vo->setProdSku($sku);

        $pcc_vo = new \ProductCustomClassificationVo();
        $pcc_vo_old = $this->getDao('ProductCustomClassification')->get(['sku' => $sku]);
        set_value($pcc_vo, $data['PCCVo']);
        $pcc_vo->setId($pcc_vo_old->getId());
        $pcc_vo->setSku($sku);

        // $ps_vo = new \ProductSpecVo();
        // set_value($ps_vo, $data['ProductSpecVo']);

        $psd_vo = new \ProductSpecDetailsVo();
        $psd_vo_old = $this->getDao('ProductSpecDetails')->get(['prod_sku' => $sku]);
        set_value($psd_vo, $data['ProductSpecDetailsVo']);
        $psd_vo->setId($psd_vo_old->getId());
        $psd_vo->setProdSku($sku);

        $pw_vo = new \ProductWarrantyVo();
        $pw_vo_old = $this->getDao('ProductWarranty')->get(['sku' => $sku]);
        set_value($pw_vo, $data['ProductWarrantyVo']);
        $pw_vo->setId($pw_vo_old->getId());
        $pw_vo->setSku($sku);

        $pk_vo = new \ProductKeywordVo();
        $pk_vo_old = $this->getDao('ProductKeyword')->get(['sku' => $sku]);
        set_value($pk_vo, $data['ProductKeywordVo']);
        $pk_vo->setId($pk_vo_old->getId());
        $pk_vo->setSku($sku);

        $pc_vo = new \ProductContentVo();
        $pc_vo_old = $this->getDao('ProductContent')->get(['prod_sku' => $sku]);
        set_value($pc_vo, $data['ProductContentVo']);
        $pc_vo->setId($pc_vo_old->getId());
        $pc_vo->setProdSku($sku);

        $pi_vo = new \ProductImageVo();
        $pi_vo_old = $this->getDao('ProductImage')->get(['sku' => $sku]);
        set_value($pi_vo, $data['ProductImageVo']);
        $pi_vo->setId($pi_vo_old->getId());
        $pi_vo->setSku($sku);

        $pce_vo = new \ProductContentExtendVo();
        $pce_vo_old = $this->getDao('ProductContentExtend')->get(['sku' => $sku]);
        set_value($pce_vo, $data['ProductContentExtendVo']);
        $pce_vo->setId($pce_vo_old->getId());
        $pce_vo->setSku($sku);

        $this->getDao('Product')->db->trans_start();
        $this->getDao('Product')->update($p_vo);
        $this->getDao('SupplierProd')->update($sp_vo);
        $this->getDao('ProductCustomClassification')->update($pcc_vo);
        $this->getDao('ProductSpecDetails')->update($psd_vo);
        $this->getDao('ProductWarranty')->update($pw_vo);
        $this->getDao('ProductKeyword')->update($pk_vo);
        $this->getDao('ProductContent')->update($pc_vo);
        $this->getDao('ProductImage')->update($pi_vo);
        $this->getDao('ProductContentExtend')->update($pce_vo);
        $this->getDao('Product')->db->trans_complete();
    }

    // private function create()
    // {
    //     $productVo->setSku($sku);
    //     $productVo->setProdGrgCd($prod_grp_cd);
    //     $supplierProdVo->setSku($sku);
    //     $pccVo->setSku($sku);

    //     $this->getDao->tran_start();
    //     $result_1 = $this->getProductDao()->insert($productVo);
    //     $result_2 = $this->getSupplierProdDao()->insert($supplierProdVo);
    //     $result_3 = $this->getProductCustomClassificationDao()->insert($pccVo);
    //     if ($result_1 && $result_2 && $result_3) {
    //         $this->getDao()->tran_complete();
    //     } else {
    //         $this->getDao()->tran_rollback();
    //     }

    //     return $sku;
    // }

    // private function createContent(MediaInterface $mediaObj = null)
    // {
    //     $this->getDao()->tran_start();
    //     $sku = $this->create();

    //     if ($skuMappingVo->getExtSku()) {
    //         $skuMappingVo->setSku($sku);
    //         $this->getDao('skuMapping')->insert($skuMappingVo);
    //     }


    //     $this->getDao('productSpec')->insert($productSpecVo);

    //     $productSpecDetailsVo->setSku($sku);
    //     $this->getDao('productSpecDetails')->insert($productSpecDetailsVo);

    //     if ($mediaObj) {
    //         $mediaObj->create();
    //     }

    //     $productWarrantyVo->setSku($sku);
    //     $this->getDao('productWarranty')->insert($productWarrantyVo);
    //     $obj = $this->getProductCustomClassificationDao()->get($where);
    //     $this->getProductCustomClassificationDao()->update();
    //     $this->getProductKeywordDao()->delete($where);
    //     $this->getProductKeywordDao()->insert($where);
    //     $this->getProductContentDao()->delete($where);
    //     $this->getProductContentDao()->insert($where);
    //     $this->getProductContentExtendDao()->delete($where);
    //     $this->getProductContentExtendDao()->insert($where);
    //     $this->getProductFeedDao()->insert();

    //     $this->getDao()->tran_complete();
    // }
}
