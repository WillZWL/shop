<?php
class SoCreditChkVo extends \BaseVo
{
    private $id;
    private $so_no;
    private $card_holder = '';
    private $card_type = '';
    private $card_no = '';
    private $card_bin = '';
    private $card_last_4 = '';
    private $card_exp_month = '';
    private $card_exp_year = '';
    private $card_start_month = '';
    private $card_start_year = '';
    private $card_issue_no = '';
    private $fd_proc_status = '0';
    private $fd_status = '0';
    private $cc_action = '0';
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

    public function setCardHolder($card_holder)
    {
        $this->card_holder = $card_holder;
    }

    public function getCardHolder()
    {
        return $this->card_holder;
    }

    public function setCardType($card_type)
    {
        $this->card_type = $card_type;
    }

    public function getCardType()
    {
        return $this->card_type;
    }

    public function setCardNo($card_no)
    {
        $this->card_no = $card_no;
    }

    public function getCardNo()
    {
        return $this->card_no;
    }

    public function setCardBin($card_bin)
    {
        $this->card_bin = $card_bin;
    }

    public function getCardBin()
    {
        return $this->card_bin;
    }

    public function setCardLast4($card_last_4)
    {
        $this->card_last_4 = $card_last_4;
    }

    public function getCardLast4()
    {
        return $this->card_last_4;
    }

    public function setCardExpMonth($card_exp_month)
    {
        $this->card_exp_month = $card_exp_month;
    }

    public function getCardExpMonth()
    {
        return $this->card_exp_month;
    }

    public function setCardExpYear($card_exp_year)
    {
        $this->card_exp_year = $card_exp_year;
    }

    public function getCardExpYear()
    {
        return $this->card_exp_year;
    }

    public function setCardStartMonth($card_start_month)
    {
        $this->card_start_month = $card_start_month;
    }

    public function getCardStartMonth()
    {
        return $this->card_start_month;
    }

    public function setCardStartYear($card_start_year)
    {
        $this->card_start_year = $card_start_year;
    }

    public function getCardStartYear()
    {
        return $this->card_start_year;
    }

    public function setCardIssueNo($card_issue_no)
    {
        $this->card_issue_no = $card_issue_no;
    }

    public function getCardIssueNo()
    {
        return $this->card_issue_no;
    }

    public function setFdProcStatus($fd_proc_status)
    {
        $this->fd_proc_status = $fd_proc_status;
    }

    public function getFdProcStatus()
    {
        return $this->fd_proc_status;
    }

    public function setFdStatus($fd_status)
    {
        $this->fd_status = $fd_status;
    }

    public function getFdStatus()
    {
        return $this->fd_status;
    }

    public function setCcAction($cc_action)
    {
        $this->cc_action = $cc_action;
    }

    public function getCcAction()
    {
        return $this->cc_action;
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
