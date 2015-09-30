<?php
class SupplierStatusDto
{
    private $sku;
    private $ext_sku;
    private $prod_name;
    private $supplier_id;
    private $supplier_name;
    private $supplier_status;
    private $supplier_status_desc;

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setExtSku($ext_sku)
    {
        $this->ext_sku = $ext_sku;
    }

    public function getExtSku()
    {
        return $this->ext_sku;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setSupplierId($supplier_id)
    {
        $this->supplier_id = $supplier_id;
    }

    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    public function setSupplierName($supplier_name)
    {
        $this->supplier_name = $supplier_name;
    }

    public function getSupplierName()
    {
        return $this->supplier_name;
    }

    public function setSupplierStatus($supplier_status)
    {
        $this->supplier_status = $supplier_status;
    }

    public function getSupplierStatus()
    {
        return $this->supplier_status;
    }

    public function setSupplierStatusDesc($supplier_status_desc)
    {
        $this->supplier_status_desc = $supplier_status_desc;
    }

    public function getSupplierStatusDesc()
    {
        return $this->supplier_status_desc;
    }

}
