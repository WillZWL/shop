<?php
class ProductHscodeReportDto
{
    private $mastersku;
    private $sku;
    private $product;
    private $category;
    private $request_by;
    private $approval_date;
    private $request_date;
    private $approved_by;
    private $reason;

    public function setMastersku($mastersku)
    {
        $this->mastersku = $mastersku;
    }

    public function getMastersku()
    {
        return $this->mastersku;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setProduct($product)
    {
        $this->product = $product;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setRequestBy($request_by)
    {
        $this->request_by = $request_by;
    }

    public function getRequestBy()
    {
        return $this->request_by;
    }

    public function setApprovalDate($approval_date)
    {
        $this->approval_date = $approval_date;
    }

    public function getApprovalDate()
    {
        return $this->approval_date;
    }

    public function setRequestDate($request_date)
    {
        $this->request_date = $request_date;
    }

    public function getRequestDate()
    {
        return $this->request_date;
    }

    public function setApprovedBy($approved_by)
    {
        $this->approved_by = $approved_by;
    }

    public function getApprovedBy()
    {
        return $this->approved_by;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function getReason()
    {
        return $this->reason;
    }

}
