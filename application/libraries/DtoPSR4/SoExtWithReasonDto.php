<?php
class SoExtWithReasonDto
{
    private $so_no;
    private $reason_id;
    private $reason;
    private $reason_display_name;
    private $require_payment;
    private $notes;
    private $offline_fee = "0.00";
    private $vatexempt;
    private $acked;
    private $fulfilled;
    private $conv_site_id;
    private $conv_status;
    private $conv_site_ref;
    private $voucher_code;
    private $voucher_detail_id;
    private $licence_key;
    private $ls_time_entered;
    private $entity_id;
    private $create_on = "0000-00-00 00:00:00";
    private $create_at;
    private $create_by;
    private $modify_on;
    private $modify_at;
    private $modify_by;

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setReasonId($reason_id)
    {
        $this->reason_id = $reason_id;
    }

    public function getReasonId()
    {
        return $this->reason_id;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setReasonDisplayName($reason_display_name)
    {
        $this->reason_display_name = $reason_display_name;
    }

    public function getReasonDisplayName()
    {
        return $this->reason_display_name;
    }

    public function setRequirePayment($require_payment)
    {
        $this->require_payment = $require_payment;
    }

    public function getRequirePayment()
    {
        return $this->require_payment;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setOfflineFee($offline_fee)
    {
        $this->offline_fee = $offline_fee;
    }

    public function getOfflineFee()
    {
        return $this->offline_fee;
    }

    public function setVatexempt($vatexempt)
    {
        $this->vatexempt = $vatexempt;
    }

    public function getVatexempt()
    {
        return $this->vatexempt;
    }

    public function setAcked($acked)
    {
        $this->acked = $acked;
    }

    public function getAcked()
    {
        return $this->acked;
    }

    public function setFulfilled($fulfilled)
    {
        $this->fulfilled = $fulfilled;
    }

    public function getFulfilled()
    {
        return $this->fulfilled;
    }

    public function setConvSiteId($conv_site_id)
    {
        $this->conv_site_id = $conv_site_id;
    }

    public function getConvSiteId()
    {
        return $this->conv_site_id;
    }

    public function setConvStatus($conv_status)
    {
        $this->conv_status = $conv_status;
    }

    public function getConvStatus()
    {
        return $this->conv_status;
    }

    public function setConvSiteRef($conv_site_ref)
    {
        $this->conv_site_ref = $conv_site_ref;
    }

    public function getConvSiteRef()
    {
        return $this->conv_site_ref;
    }

    public function setVoucherCode($voucher_code)
    {
        $this->voucher_code = $voucher_code;
    }

    public function getVoucherCode()
    {
        return $this->voucher_code;
    }

    public function setVoucherDetailId($voucher_detail_id)
    {
        $this->voucher_detail_id = $voucher_detail_id;
    }

    public function getVoucherDetailId()
    {
        return $this->voucher_detail_id;
    }

    public function setLicenceKey($licence_key)
    {
        $this->licence_key = $licence_key;
    }

    public function getLicenceKey()
    {
        return $this->licence_key;
    }

    public function setLsTimeEntered($ls_time_entered)
    {
        $this->ls_time_entered = $ls_time_entered;
    }

    public function getLsTimeEntered()
    {
        return $this->ls_time_entered;
    }

    public function setEntityId($entity_id)
    {
        $this->entity_id = $entity_id;
    }

    public function getEntityId()
    {
        return $this->entity_id;
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

}
