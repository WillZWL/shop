<?php
class InventoryVo extends \BaseVo
{
    private $id;
    private $warehouse_id;
    private $prod_sku;
    private $inventory;
    private $git;
    private $surplus_qty;
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = 'CURRENT_TIMESTAMP';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setWarehouseId($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
    }

    public function getWarehouseId()
    {
        return $this->warehouse_id;
    }

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setInventory($inventory)
    {
        $this->inventory = $inventory;
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function setGit($git)
    {
        $this->git = $git;
    }

    public function getGit()
    {
        return $this->git;
    }

    public function setSurplusQty($surplus_qty)
    {
        $this->surplus_qty = $surplus_qty;
    }

    public function getSurplusQty()
    {
        return $this->surplus_qty;
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

    public function getPrimaryKey()
    {
        return $this->primary_key;
    }

    public function getIncrementField()
    {
        return $this->increment_field;
    }
}
