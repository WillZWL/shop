<?php
class SoExtendVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $order_reason = '0';
    private $notes = '';
    private $offline_fee = '0.00';
    private $vatexempt = '0';
    private $acked = '';
    private $fulfilled = '';
    private $conv_site_id = '';
    private $conv_status = '0';
    private $conv_site_ref = '';
    private $voucher_code = '';
    private $voucher_detail_id = '0';
    private $licence_key = '';
    private $ls_time_entered = '';
    private $entity_id = '0';
    private $aftership_status = '0';
    private $aftership_checkpoint = '0000-00-00 00:00:00';
    private $aftership_token = '';
    private $into_wms_status = '0';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '2130706433';
    private $create_by = 'system';
    private $modify_on = '';
    private $modify_at = '2130706433';
    private $modify_by = 'system';

    private $primary_key = ['id'];
    private $increment_field = 'id';

    public function setId($id)
    {
        if ($id != null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSoNo($so_no)
    {
        if ($so_no != null) {
            $this->so_no = $so_no;
        }
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setOrderReason($order_reason)
    {
        if ($order_reason != null) {
            $this->order_reason = $order_reason;
        }
    }

    public function getOrderReason()
    {
        return $this->order_reason;
    }

    public function setNotes($notes)
    {
        if ($notes != null) {
            $this->notes = $notes;
        }
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function setOfflineFee($offline_fee)
    {
        if ($offline_fee != null) {
            $this->offline_fee = $offline_fee;
        }
    }

    public function getOfflineFee()
    {
        return $this->offline_fee;
    }

    public function setVatexempt($vatexempt)
    {
        if ($vatexempt != null) {
            $this->vatexempt = $vatexempt;
        }
    }

    public function getVatexempt()
    {
        return $this->vatexempt;
    }

    public function setAcked($acked)
    {
        if ($acked != null) {
            $this->acked = $acked;
        }
    }

    public function getAcked()
    {
        return $this->acked;
    }

    public function setFulfilled($fulfilled)
    {
        if ($fulfilled != null) {
            $this->fulfilled = $fulfilled;
        }
    }

    public function getFulfilled()
    {
        return $this->fulfilled;
    }

    public function setConvSiteId($conv_site_id)
    {
        if ($conv_site_id != null) {
            $this->conv_site_id = $conv_site_id;
        }
    }

    public function getConvSiteId()
    {
        return $this->conv_site_id;
    }

    public function setConvStatus($conv_status)
    {
        if ($conv_status != null) {
            $this->conv_status = $conv_status;
        }
    }

    public function getConvStatus()
    {
        return $this->conv_status;
    }

    public function setConvSiteRef($conv_site_ref)
    {
        if ($conv_site_ref != null) {
            $this->conv_site_ref = $conv_site_ref;
        }
    }

    public function getConvSiteRef()
    {
        return $this->conv_site_ref;
    }

    public function setVoucherCode($voucher_code)
    {
        if ($voucher_code != null) {
            $this->voucher_code = $voucher_code;
        }
    }

    public function getVoucherCode()
    {
        return $this->voucher_code;
    }

    public function setVoucherDetailId($voucher_detail_id)
    {
        if ($voucher_detail_id != null) {
            $this->voucher_detail_id = $voucher_detail_id;
        }
    }

    public function getVoucherDetailId()
    {
        return $this->voucher_detail_id;
    }

    public function setLicenceKey($licence_key)
    {
        if ($licence_key != null) {
            $this->licence_key = $licence_key;
        }
    }

    public function getLicenceKey()
    {
        return $this->licence_key;
    }

    public function setLsTimeEntered($ls_time_entered)
    {
        if ($ls_time_entered != null) {
            $this->ls_time_entered = $ls_time_entered;
        }
    }

    public function getLsTimeEntered()
    {
        return $this->ls_time_entered;
    }

    public function setEntityId($entity_id)
    {
        if ($entity_id != null) {
            $this->entity_id = $entity_id;
        }
    }

    public function getEntityId()
    {
        return $this->entity_id;
    }

    public function setAftershipStatus($aftership_status)
    {
        if ($aftership_status != null) {
            $this->aftership_status = $aftership_status;
        }
    }

    public function getAftershipStatus()
    {
        return $this->aftership_status;
    }

    public function setAftershipCheckpoint($aftership_checkpoint)
    {
        if ($aftership_checkpoint != null) {
            $this->aftership_checkpoint = $aftership_checkpoint;
        }
    }

    public function getAftershipCheckpoint()
    {
        return $this->aftership_checkpoint;
    }

    public function setAftershipToken($aftership_token)
    {
        if ($aftership_token != null) {
            $this->aftership_token = $aftership_token;
        }
    }

    public function getAftershipToken()
    {
        return $this->aftership_token;
    }

    public function setIntoWmsStatus($into_wms_status)
    {
        if ($into_wms_status != null) {
            $this->into_wms_status = $into_wms_status;
        }
    }

    public function getIntoWmsStatus()
    {
        return $this->into_wms_status;
    }

    public function setCreateOn($create_on)
    {
        if ($create_on != null) {
            $this->create_on = $create_on;
        }
    }

    public function getCreateOn()
    {
        return $this->create_on;
    }

    public function setCreateAt($create_at)
    {
        if ($create_at != null) {
            $this->create_at = $create_at;
        }
    }

    public function getCreateAt()
    {
        return $this->create_at;
    }

    public function setCreateBy($create_by)
    {
        if ($create_by != null) {
            $this->create_by = $create_by;
        }
    }

    public function getCreateBy()
    {
        return $this->create_by;
    }

    public function setModifyOn($modify_on)
    {
        if ($modify_on != null) {
            $this->modify_on = $modify_on;
        }
    }

    public function getModifyOn()
    {
        return $this->modify_on;
    }

    public function setModifyAt($modify_at)
    {
        if ($modify_at != null) {
            $this->modify_at = $modify_at;
        }
    }

    public function getModifyAt()
    {
        return $this->modify_at;
    }

    public function setModifyBy($modify_by)
    {
        if ($modify_by != null) {
            $this->modify_by = $modify_by;
        }
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
