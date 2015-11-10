<?php
class SourcingListDto
{
    private $master_sku;
    private $item_sku;
    private $platform_qty;
    private $required_qty;
    private $prioritized_qty;
    private $supplier_id;
    private $prod_name;
    private $sourcing_status;
    private $list_date;
    private $supplier_curr_id;
    private $supplier_cost;
    private $budget_pcent;
    private $budget;
    private $sourced_qty;
    private $sourced_pcent;
    private $comments;
    private $inventory;
    private $create_on = "0000-00-00 00:00:00";
    private $create_at = "127.0.0.1";
    private $create_by;
    private $modify_on;
    private $modify_at = "127.0.0.1";
    private $modify_by;
    private $clearance;

    public function setMasterSku($master_sku)
    {
        $this->master_sku = $master_sku;
    }

    public function getMasterSku()
    {
        return $this->master_sku;
    }

    public function setItemSku($item_sku)
    {
        $this->item_sku = $item_sku;
    }

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setPlatformQty($platform_qty)
    {
        $this->platform_qty = $platform_qty;
    }

    public function getPlatformQty()
    {
        return $this->platform_qty;
    }

    public function setRequiredQty($required_qty)
    {
        $this->required_qty = $required_qty;
    }

    public function getRequiredQty()
    {
        return $this->required_qty;
    }

    public function setPrioritizedQty($prioritized_qty)
    {
        $this->prioritized_qty = $prioritized_qty;
    }

    public function getPrioritizedQty()
    {
        return $this->prioritized_qty;
    }

    public function setSupplierId($supplier_id)
    {
        $this->supplier_id = $supplier_id;
    }

    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setSourcingStatus($sourcing_status)
    {
        $this->sourcing_status = $sourcing_status;
    }

    public function getSourcingStatus()
    {
        return $this->sourcing_status;
    }

    public function setListDate($list_date)
    {
        $this->list_date = $list_date;
    }

    public function getListDate()
    {
        return $this->list_date;
    }

    public function setSupplierCurrId($supplier_curr_id)
    {
        $this->supplier_curr_id = $supplier_curr_id;
    }

    public function getSupplierCurrId()
    {
        return $this->supplier_curr_id;
    }

    public function setSupplierCost($supplier_cost)
    {
        $this->supplier_cost = $supplier_cost;
    }

    public function getSupplierCost()
    {
        return $this->supplier_cost;
    }

    public function setBudgetPcent($budget_pcent)
    {
        $this->budget_pcent = $budget_pcent;
    }

    public function getBudgetPcent()
    {
        return $this->budget_pcent;
    }

    public function setBudget($budget)
    {
        $this->budget = $budget;
    }

    public function getBudget()
    {
        return $this->budget;
    }

    public function setSourcedQty($sourced_qty)
    {
        $this->sourced_qty = $sourced_qty;
    }

    public function getSourcedQty()
    {
        return $this->sourced_qty;
    }

    public function setSourcedPcent($sourced_pcent)
    {
        $this->sourced_pcent = $sourced_pcent;
    }

    public function getSourcedPcent()
    {
        return $this->sourced_pcent;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setInventory($inventory)
    {
        $this->inventory = $inventory;
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        $this->create_by = $create_by;
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        $this->modify_on = $modify_on;
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        $this->modify_at = $modify_at;
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        $this->modify_by = $modify_by;
    }

    public function getModifyBy()
    {
        return $this->modify_by;
    }

    public function setClearance($clearance)
    {
        $this->clearance = $clearance;
    }

    public function getClearance()
    {
        return $this->clearance;
    }

}
