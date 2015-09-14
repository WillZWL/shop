<?php
namespace ESG\Panther\Service;

class ProductCreationService extends BaseService
{
    private $vos = [
        'skuMappingVo',
        'productVo',
        'supplierProdVo',
    ];

    private $data;

    // public function __construct()
    // {
    //     $this->vos['skuMappingVo'] = new \SkuMappingVo();
    // }

    public function setProductVo(&$p_vo, $sku, $skuGroup)
    {
        $p_vo->setSku($sku);
        $p_vo->setProdGrpCd($skuGroup);
        $p_vo->setVersionId('AA');
        $p_vo->setColourId('BK');
        $p_vo->setname('TestProduct');
        $p_vo->setFreightCatId(1);
        $p_vo->setCatId(1);
        $p_vo->setSubCatId(1);
        $p_vo->setSubSubCatId(1);
        $p_vo->setBrandId(1);
        $p_vo->setClearance(0);
        $p_vo->setSurplusQuantity(0);
        $p_vo->setSlowMove7Days(1);
        $p_vo->setQuantity(7);
        $p_vo->setDisplayQuantity(9);
        $p_vo->setWebsiteQuantity(23);
        $p_vo->setExDemo(1);
        $p_vo->setChinaOem(1);
        $p_vo->setRrp(2.34);
        $p_vo->setImage('jpg');
        $p_vo->setFlash('');
        $p_vo->setYoutubeId('');
        $p_vo->setEan('');
        $p_vo->setMpn('');
        $p_vo->setUpc('');
        $p_vo->setDiscount('0.90');
        $p_vo->setProcStatus('1');
        $p_vo->setWebsiteStatus('I');
        $p_vo->setSourcingStatus('A');
        $p_vo->setExpectedDeliveryDate('0000-00-00 00:00:00');
        $p_vo->setWarrantyInMonth('12');
        $p_vo->setCatUpselling(1);
        $p_vo->setLangRestricted(1);
        $p_vo->setShipmentRestrictedType(0);
        $p_vo->setStatus(1);
    }

    public function process($data = '')
    {
        if (!isset($data['SkuMappingVo'])) {
            return false;
        }

        $extSku = $data['SkuMappingVo']->getExtSku();

        if ($extSku) {
            $this->insertNewProduct($data, true);
        } else {
            $this->insertNewProduct($data, false);
        }
    }

    public function insertNewProduct($has_ext_sku = true)
    {
        $sku = $this->getDao('Product')->getNewSku();
        $skuGroup = $this->getDao('Product')->getNewProductGroup();

        $p_vo = new \ProductVo();
        set_value($p_vo, $data['ProductVo']);
        $p_vo->setSku($sku);
        $p_vo->setProdGrgCd($skuGroup);

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
        $pce_vo->setSku($sku);

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

    private function create()
    {
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

    private function createContent(MediaInterface $mediaObj = null)
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
