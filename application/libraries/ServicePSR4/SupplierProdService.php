<?php
namespace ESG\Panther\Service;

class SupplierProdService extends BaseService
{
    public function createNewSupplierProd($sku, $oldObj)
    {
        if ( ! $this->getDao('Product')->get(['sku' => $sku])) {
            return false;
        }

        $newObj = new \SupplierProdVo();
        $newObj->setSupplierId((string)$oldObj->supplier_id);
        $newObj->setProdSku($sku);
        $newObj->setOrderDefault((string)$oldObj->supplier_id);
        $this->updateSupplierProd($newObj, $oldObj);

        return $newObj;
    }

    public function updateSupplierProd(&$newObj, $oldObj)
    {
        $newObj->setSupplierId((string) $oldObj->supplier_id);
        $newObj->setCurrencyId((string) $oldObj->currency_id);
        $newObj->setCost((string) $oldObj->cost);
        $newObj->setPricehkd((string) $oldObj->pricehkd);
        $newObj->setLeadDay((string) $oldObj->lead_day);
        $newObj->setMoq((string) $oldObj->moq);
        $newObj->setLocation((string) $oldObj->location);
        $newObj->setRegion((string) $oldObj->region);
        $newObj->setOrderDefault((string) $oldObj->order_default);
        $newObj->setRegionDefault((string) $oldObj->region_default);
        $newObj->setSupplierStatus((string) $oldObj->supplier_status);
        $newObj->setComments((string) $oldObj->comments);
    }
}
