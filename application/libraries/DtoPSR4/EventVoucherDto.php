<?php
class EventVoucherDto
{
    private $event_id;
    private $so_no;
    private $voucher_detail_id;
    private $voucher_code;

    public function setEventId($event_id)
    {
        $this->event_id = $event_id;
    }

    public function getEventId()
    {
        return $this->event_id;
    }

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setVoucherDetailId($voucher_detail_id)
    {
        $this->voucher_detail_id = $voucher_detail_id;
    }

    public function getVoucherDetailId()
    {
        return $this->voucher_detail_id;
    }

    public function setVoucherCode($voucher_code)
    {
        $this->voucher_code = $voucher_code;
    }

    public function getVoucherCode()
    {
        return $this->voucher_code;
    }

}
