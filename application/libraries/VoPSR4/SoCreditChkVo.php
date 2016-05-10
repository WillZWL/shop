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

    public function setCardHolder($card_holder)
    {
        if ($card_holder !== null) {
            $this->card_holder = $card_holder;
        }
    }

    public function getCardHolder()
    {
        return $this->card_holder;
    }

    public function setCardType($card_type)
    {
        if ($card_type !== null) {
            $this->card_type = $card_type;
        }
    }

    public function getCardType()
    {
        return $this->card_type;
    }

    public function setCardNo($card_no)
    {
        if ($card_no !== null) {
            $this->card_no = $card_no;
        }
    }

    public function getCardNo()
    {
        return $this->card_no;
    }

    public function setCardBin($card_bin)
    {
        if ($card_bin !== null) {
            $this->card_bin = $card_bin;
        }
    }

    public function getCardBin()
    {
        return $this->card_bin;
    }

    public function setCardLast4($card_last_4)
    {
        if ($card_last_4 !== null) {
            $this->card_last_4 = $card_last_4;
        }
    }

    public function getCardLast4()
    {
        return $this->card_last_4;
    }

    public function setCardExpMonth($card_exp_month)
    {
        if ($card_exp_month !== null) {
            $this->card_exp_month = $card_exp_month;
        }
    }

    public function getCardExpMonth()
    {
        return $this->card_exp_month;
    }

    public function setCardExpYear($card_exp_year)
    {
        if ($card_exp_year !== null) {
            $this->card_exp_year = $card_exp_year;
        }
    }

    public function getCardExpYear()
    {
        return $this->card_exp_year;
    }

    public function setCardStartMonth($card_start_month)
    {
        if ($card_start_month !== null) {
            $this->card_start_month = $card_start_month;
        }
    }

    public function getCardStartMonth()
    {
        return $this->card_start_month;
    }

    public function setCardStartYear($card_start_year)
    {
        if ($card_start_year !== null) {
            $this->card_start_year = $card_start_year;
        }
    }

    public function getCardStartYear()
    {
        return $this->card_start_year;
    }

    public function setCardIssueNo($card_issue_no)
    {
        if ($card_issue_no !== null) {
            $this->card_issue_no = $card_issue_no;
        }
    }

    public function getCardIssueNo()
    {
        return $this->card_issue_no;
    }

    public function setFdProcStatus($fd_proc_status)
    {
        if ($fd_proc_status !== null) {
            $this->fd_proc_status = $fd_proc_status;
        }
    }

    public function getFdProcStatus()
    {
        return $this->fd_proc_status;
    }

    public function setFdStatus($fd_status)
    {
        if ($fd_status !== null) {
            $this->fd_status = $fd_status;
        }
    }

    public function getFdStatus()
    {
        return $this->fd_status;
    }

    public function setCcAction($cc_action)
    {
        if ($cc_action !== null) {
            $this->cc_action = $cc_action;
        }
    }

    public function getCcAction()
    {
        return $this->cc_action;
    }

}
