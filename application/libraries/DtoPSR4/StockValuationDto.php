<?php
class StockValuationDto
{
    private $cat_name;
    private $sub_cat_name;
    private $sub_sub_cat_name;
    private $prod_name;
    private $prod_sku;
    private $log_sku;
    private $warehouse_id;
    private $inventory;
    private $git;
    private $value_per_piece;
    private $total_inv_value;
    private $total_git_value;
    private $total_value;

    public function setCatName($cat_name)
    {
        $this->cat_name = $cat_name;
    }

    public function getCatName()
    {
        return $this->cat_name;
    }

    public function setSubCatName($sub_cat_name)
    {
        $this->sub_cat_name = $sub_cat_name;
    }

    public function getSubCatName()
    {
        return $this->sub_cat_name;
    }

    public function setSubSubCatName($sub_sub_cat_name)
    {
        $this->sub_sub_cat_name = $sub_sub_cat_name;
    }

    public function getSubSubCatName()
    {
        return $this->sub_sub_cat_name;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setLogSku($log_sku)
    {
        $this->log_sku = $log_sku;
    }

    public function getLogSku()
    {
        return $this->log_sku;
    }

    public function setWarehouseId($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
    }

    public function getWarehouseId()
    {
        return $this->warehouse_id;
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

    public function setValuePerPiece($value_per_piece)
    {
        $this->value_per_piece = $value_per_piece;
    }

    public function getValuePerPiece()
    {
        return $this->value_per_piece;
    }

    public function setTotalInvValue($total_inv_value)
    {
        $this->total_inv_value = $total_inv_value;
    }

    public function getTotalInvValue()
    {
        return $this->total_inv_value;
    }

    public function setTotalGitValue($total_git_value)
    {
        $this->total_git_value = $total_git_value;
    }

    public function getTotalGitValue()
    {
        return $this->total_git_value;
    }

    public function setTotalValue($total_value)
    {
        $this->total_value = $total_value;
    }

    public function getTotalValue()
    {
        return $this->total_value;
    }

}
