<?php
class OrderReasonVo extends \BaseVo
{
    private $id;
    private $reason_id;
    private $reason;
    private $reason_display_name = '';
    private $priority = '0';
    private $option_in_special = '0';
    private $option_in_manual = '0';
    private $option_in_phone = '0';
    private $require_payment = '0';
    private $Header_TranCode = '';
    private $Header_CustCode = '';
    private $status = '0';
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

    public function setReasonId($reason_id)
    {
        if ($reason_id != null) {
            $this->reason_id = $reason_id;
        }
    }

    public function getReasonId()
    {
        return $this->reason_id;
    }

    public function setReason($reason)
    {
        if ($reason != null) {
            $this->reason = $reason;
        }
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setReasonDisplayName($reason_display_name)
    {
        if ($reason_display_name != null) {
            $this->reason_display_name = $reason_display_name;
        }
    }

    public function getReasonDisplayName()
    {
        return $this->reason_display_name;
    }

    public function setPriority($priority)
    {
        if ($priority != null) {
            $this->priority = $priority;
        }
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setOptionInSpecial($option_in_special)
    {
        if ($option_in_special != null) {
            $this->option_in_special = $option_in_special;
        }
    }

    public function getOptionInSpecial()
    {
        return $this->option_in_special;
    }

    public function setOptionInManual($option_in_manual)
    {
        if ($option_in_manual != null) {
            $this->option_in_manual = $option_in_manual;
        }
    }

    public function getOptionInManual()
    {
        return $this->option_in_manual;
    }

    public function setOptionInPhone($option_in_phone)
    {
        if ($option_in_phone != null) {
            $this->option_in_phone = $option_in_phone;
        }
    }

    public function getOptionInPhone()
    {
        return $this->option_in_phone;
    }

    public function setRequirePayment($require_payment)
    {
        if ($require_payment != null) {
            $this->require_payment = $require_payment;
        }
    }

    public function getRequirePayment()
    {
        return $this->require_payment;
    }

    public function setHeaderTranCode($Header_TranCode)
    {
        if ($Header_TranCode != null) {
            $this->Header_TranCode = $Header_TranCode;
        }
    }

    public function getHeaderTranCode()
    {
        return $this->Header_TranCode;
    }

    public function setHeaderCustCode($Header_CustCode)
    {
        if ($Header_CustCode != null) {
            $this->Header_CustCode = $Header_CustCode;
        }
    }

    public function getHeaderCustCode()
    {
        return $this->Header_CustCode;
    }

    public function setStatus($status)
    {
        if ($status != null) {
            $this->status = $status;
        }
    }

    public function getStatus()
    {
        return $this->status;
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
