<?php
class SoExtendVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $order_reason;
    private $notes;
    private $offline_fee = '0.00';
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
    private $aftership_status;
    private $aftership_checkpoint = '0000-00-00 00:00:00';
    private $aftership_token;
    private $into_wms_status;
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

    public function setSoNo($so_no)
    {
        $this->so_no = $so_no;
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setOrderReason($order_reason)
    {
        $this->order_reason = $order_reason;
    }

    public function getOrderReason()
    {
        return $this->order_reason;
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

    public function setAftershipStatus($aftership_status)
    {
        $this->aftership_status = $aftership_status;
    }

    public function getAftershipStatus()
    {
        return $this->aftership_status;
    }

    public function setAftershipCheckpoint($aftership_checkpoint)
    {
        $this->aftership_checkpoint = $aftership_checkpoint;
    }

    public function getAftershipCheckpoint()
    {
        return $this->aftership_checkpoint;
    }

    public function setAftershipToken($aftership_token)
    {
        $this->aftership_token = $aftership_token;
    }

    public function getAftershipToken()
    {
        return $this->aftership_token;
    }

    public function setIntoWmsStatus($into_wms_status)
    {
        $this->into_wms_status = $into_wms_status;
    }

    public function getIntoWmsStatus()
    {
        return $this->into_wms_status;
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
