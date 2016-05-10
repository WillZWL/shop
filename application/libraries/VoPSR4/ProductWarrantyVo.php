<?php
class ProductWarrantyVo extends \BaseVo
{
    private $id;
    private $sku;
    private $platform_id;
    private $warranty_in_month = '0';


    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSku($sku)
    {
        if ($sku !== null) {
            $this->sku = $sku;
        }
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setPlatformId($platform_id)
    {
        if ($platform_id !== null) {
            $this->platform_id = $platform_id;
        }
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setWarrantyInMonth($warranty_in_month)
    {
        if ($warranty_in_month !== null) {
            $this->warranty_in_month = $warranty_in_month;
        }
    }

    public function getWarrantyInMonth()
    {
        return $this->warranty_in_month;
    }

}
