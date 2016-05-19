<?php

class AutoRestockLogVo extends \BaseVo
{
    private $id;
    private $batch_id = '';
    private $sku = '';
    private $vb_sku = '';
    private $master_sku = '';
    private $prod_name = '';
    private $website_quantity = 0;
    private $displat_quantity = 0;
    private $item_cost = '0.00';
    private $supply_status = '';

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

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

    public function setBatchId($batch_id)
    {
        if ($batch_id !== null) {
            $this->batch_id = $batch_id;
        }
    }

    public function getBatchId()
    {
        return $this->batch_id;
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

    public function setVbSku($vb_sku)
    {
        if ($vb_sku !== null) {
            $this->vb_sku = $vb_sku;
        }
    }

    public function getVbSku()
    {
        return $this->vb_sku;
    }

    public function setMasterSku($master_sku)
    {
        if ($master_sku !== null) {
            $this->master_sku = $master_sku;
        }
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setProdName($prod_name)
    {
        if ($prod_name !== null) {
            $this->prod_name = $prod_name;
        }
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setWebsiteQuantity($website_quantity)
    {
        if ($website_quantity !== null) {
            $this->website_quantity = $website_quantity;
        }
    }

    public function getWebsiteQuantity()
    {
        return $this->website_quantity;
    }

    public function setDisplayQuantity($displat_quantity)
    {
        if ($displat_quantity !== null) {
            $this->displat_quantity = $displat_quantity;
        }
    }

    public function getDisplayQuantity()
    {
        return $this->displat_quantity;
    }

    public function setItemCost($item_cost)
    {
        if ($item_cost !== null) {
            $this->item_cost = $item_cost;
        }
    }

    public function getItemCost()
    {
        return $this->item_cost;
    }

    public function setSupplyStatus($supply_status)
    {
        if ($supply_status !== null) {
            $this->supply_status = $supply_status;
        }
    }

    public function getSupplyStatus()
    {
        return $this->supply_status;
    }
}