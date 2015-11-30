<?php
namespace ESG\Panther\Service;

class SupplierService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkValidSupplierCost($sku)
    {
        return $this->getDao('Supplier')->checkValidSupplierCost($sku);
    }
}


