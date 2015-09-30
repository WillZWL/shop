<?php
class RefundReasonReportDto
{
    private $rank;
    private $reason;
    private $percentage;
    private $products;

    public function getRank()
    {
        return $this->rank;
    }

    public function setRank($value)
    {
        $this->rank = $value;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setReason($value)
    {
        $this->reason = $value;
    }

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function setPercentage($value)
    {
        $this->percentage = $value;
    }

    public function getProducts()
    {
        return $this->products;
    }

    public function setProducts($value)
    {
        $this->products = $value;
    }
}

