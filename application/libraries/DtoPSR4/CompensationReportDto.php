<?php
class CompensationReportDto
{
    private $platform_id;
    private $so_no;
    private $prod_name;
    private $item_sku;
    private $request_by;
    private $approval_date;
    private $request_date;
    private $approved_by;
    private $reason;

    public function setPlatformId($platform_id)
    {
        $this->platform_id = $platform_id;
    }

    public function getPlatformId()
    {
        return $this->platform_id;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setProdName($prod_name)
    {
        $this->prod_name = $prod_name;
    }

    public function getProdName()
    {
        return $this->prod_name;
    }

    public function setItemSku($item_sku)
    {
        $this->item_sku = $item_sku;
    }

    public function getItemSku()
    {
        return $this->item_sku;
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
