<?php
class ProductComplementaryAccVo extends \BaseVo
{
    private $id;
    private $mainprod_sku;
    private $accessory_sku;
    private $dest_country_id;
    private $status = '1';


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

    public function setMainprodSku($mainprod_sku)
    {
        if ($mainprod_sku !== null) {
            $this->mainprod_sku = $mainprod_sku;
        }
    }

    public function getMainprodSku()
    {
        return $this->mainprod_sku;
    }

    public function setAccessorySku($accessory_sku)
    {
        if ($accessory_sku !== null) {
            $this->accessory_sku = $accessory_sku;
        }
    }

    public function getAccessorySku()
    {
        return $this->accessory_sku;
    }

    public function setDestCountryId($dest_country_id)
    {
        if ($dest_country_id !== null) {
            $this->dest_country_id = $dest_country_id;
        }
    }

    public function getDestCountryId()
    {
        return $this->dest_country_id;
    }

    public function setStatus($status)
    {
        if ($status !== null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

}
