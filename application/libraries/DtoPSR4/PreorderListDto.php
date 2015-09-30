<?php
class PreorderListDto
{
    private $so_no;
    private $prod_sku;
    private $prod_name;
    private $qty;
    private $expect_delivery_date;
    private $create_on;
    private $current_expected_delivery_date;
    private $multiple_items_count;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setProdSku($prod_sku)
    {
        $this->prod_sku = $prod_sku;
    }

    public function getProdSku()
    {
        return $this->prod_sku;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    public function getQty()
    {
        return $this->qty;
    }

    public function setExpectDeliveryDate($expect_delivery_date)
    {
        $this->expect_delivery_date = $expect_delivery_date;
    }

    public function getExpectDeliveryDate()
    {
        return $this->expect_delivery_date;
    }

    public function setCreateOn($create_on)
    {
        $this->create_on = $create_on;
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCurrentExpectedDeliveryDate($current_expected_delivery_date)
    {
        $this->current_expected_delivery_date = $current_expected_delivery_date;
    }

    public function getCurrentExpectedDeliveryDate()
    {
        return $this->current_expected_delivery_date;
    }

    public function setMultipleItemsCount($multiple_items_count)
    {
        $this->multiple_items_count = $multiple_items_count;
    }

    public function getMultipleItemsCount()
    {
        return $this->multiple_items_count;
    }

}
