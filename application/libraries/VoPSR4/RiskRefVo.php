<?php
class RiskRefVo extends \BaseVo
{
    private $id;
    private $payment_gateway_id;
    private $risk_ref;
    private $risk_ref_desc = '';
    private $action = '';
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

    public function setPaymentGatewayId($payment_gateway_id)
    {
        if ($payment_gateway_id != null) {
            $this->payment_gateway_id = $payment_gateway_id;
        }
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setRiskRef($risk_ref)
    {
        if ($risk_ref != null) {
            $this->risk_ref = $risk_ref;
        }
    }

    public function getRiskRef()
    {
        return $this->risk_ref;
    }

    public function setRiskRefDesc($risk_ref_desc)
    {
        if ($risk_ref_desc != null) {
            $this->risk_ref_desc = $risk_ref_desc;
        }
    }

    public function getRiskRefDesc()
    {
        return $this->risk_ref_desc;
    }

    public function setAction($action)
    {
        if ($action != null) {
            $this->action = $action;
        }
    }

    public function getAction()
    {
        return $this->action;
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
