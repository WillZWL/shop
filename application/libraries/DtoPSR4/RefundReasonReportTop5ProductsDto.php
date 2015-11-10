<?php
class RefundReasonReportTop5ProductsDto
{
    private $item_sku;
    private $item_name;
    private $frequency;

    public function getItemSku()
    {
        return $this->item_sku;
    }

    public function setItemSku($value)
    {
        $this->item_sku = $value;
    }

    public function getItemName()
    {
        return $this->item_name;
    }

    public function setItemName($value)
    {
        $this->item_name = $value;
    }

    public function getFrequency()
    {
        return $this->frequency;
    }

    public function setFrequency($value)
    {
        $this->frequency = $value;
    }

}
