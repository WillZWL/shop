<?php
class VoucherWithDetailDto
{
    private $id;
    private $voucher_id;
    private $type;
    private $party;
    private $expire_date;
    private $code;
    private $distributed;
    private $total_distribution;
    private $status;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setVoucherId($voucher_id)
    {
        $this->voucher_id = $voucher_id;
    }

    public function getVoucherId()
    {
        return $this->voucher_id;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setParty($party)
    {
        $this->party = $party;
    }

    public function getParty()
    {
        return $this->party;
    }

    public function setExpireDate($expire_date)
    {
        $this->expire_date = $expire_date;
    }

    public function getExpireDate()
    {
        return $this->expire_date;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setDistributed($distributed)
    {
        $this->distributed = $distributed;
    }

    public function getDistributed()
    {
        return $this->distributed;
    }

    public function setTotalDistribution($total_distribution)
    {
        $this->total_distribution = $total_distribution;
    }

    public function getTotalDistribution()
    {
        return $this->total_distribution;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

}
