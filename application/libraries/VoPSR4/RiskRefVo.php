<?php
class RiskRefVo extends \BaseVo
{
    private $id;
    private $payment_gateway_id;
    private $risk_ref;
    private $risk_ref_desc = '';
    private $action = '';


    public function setId($id)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPaymentGatewayId($payment_gateway_id)
    {
        if ($payment_gateway_id !== null) {
            $this->payment_gateway_id = $payment_gateway_id;
        }
    }

    public function getPaymentGatewayId()
    {
        return $this->payment_gateway_id;
    }

    public function setRiskRef($risk_ref)
    {
        if ($risk_ref !== null) {
            $this->risk_ref = $risk_ref;
        }
    }

    public function getRiskRef()
    {
        return $this->risk_ref;
    }

    public function setRiskRefDesc($risk_ref_desc)
    {
        if ($risk_ref_desc !== null) {
            $this->risk_ref_desc = $risk_ref_desc;
        }
    }

    public function getRiskRefDesc()
    {
        return $this->risk_ref_desc;
    }

    public function setAction($action)
    {
        if ($action !== null) {
            $this->action = $action;
        }
    }

    public function getAction()
    {
        return $this->action;
    }

}
