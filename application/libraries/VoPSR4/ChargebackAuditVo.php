<?php

class ChargebackAuditVo extends \BaseVo
{
    private $id;
    private $chargeback_id;
    private $so_no;
    private $chargeback_status_id;
    private $chargeback_reason_id;
    private $chargeback_reason;
    private $chargeback_remark_id;
    private $chargeback_remark;
    private $document;
    private $remarks;

    protected $primary_key = ['id'];
    protected $increment_field = 'id';

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

    public function setChargebackId($chargeback_id)
    {
        if ($chargeback_id !== null) {
            $this->chargeback_id = $chargeback_id;
        }
    }

    public function getChargebackId()
    {
        return $this->chargeback_id;
    }

    public function setSoNo($so_no)
    {
        if ($so_no !== null) {
            $this->so_no = $so_no;
        }
    }

    public function getSoNo()
    {
        return $this->so_no;
    }

    public function setChargebackStatusId($chargeback_status_id)
    {
        if ($chargeback_status_id !== null) {
            $this->chargeback_status_id = $chargeback_status_id;
        }
    }

    public function getChargebackStatusId()
    {
        return $this->chargeback_status_id;
    }

    public function setChargebackReasonId($chargeback_reason_id)
    {
        if ($chargeback_reason_id !== null) {
            $this->chargeback_reason_id = $chargeback_reason_id;
        }
    }

    public function getChargebackReasonId()
    {
        return $this->chargeback_reason_id;
    }

    public function setChargebackReason($chargeback_reason)
    {
        if ($chargeback_reason !== null) {
            $this->chargeback_reason = $chargeback_reason;
        }
    }

    public function getChargebackReason()
    {
        return $this->chargeback_reason;
    }

    public function setChargebackRemarkId($chargeback_remark_id)
    {
        if ($chargeback_remark_id !== null) {
            $this->chargeback_remark_id = $chargeback_remark_id;
        }
    }

    public function getChargebackRemarkId()
    {
        return $this->chargeback_remark_id;
    }

    public function setChargebackRemark($chargeback_remark)
    {
        if ($chargeback_remark !== null) {
            $this->chargeback_remark = $chargeback_remark;
        }
    }

    public function getChargebackRemark()
    {
        return $this->chargeback_remark;
    }

    public function setDocument($document)
    {
        if ($document !== null) {
            $this->document = $document;
        }
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function setRemarks($remarks)
    {
        if ($remarks !== null) {
            $this->remarks = $remarks;
        }
    }

    public function getRemarks()
    {
        return $this->remarks;
    }

}
