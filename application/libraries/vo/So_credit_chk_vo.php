<?php
include_once 'Base_vo.php';

class So_credit_chk_vo extends Base_vo
{

    //class variable
    private $so_no;
    private $t3m_is_sent = 'N';
    private $t3m_in_file;
    private $t3m_result;
    private $card_holder;
    private $card_type;
    private $card_no;
    private $card_bin;
    private $card_last4;
    private $card_exp_month;
    private $card_exp_year;
    private $card_start_month;
    private $card_start_year;
    private $card_issue_no;
    private $fd_proc_status = '0';
    private $fd_status = '0';
    private $cc_action = '0';
    private $create_on = '0000-00-00 00:00:00';
    private $create_at = '127.0.0.1';
    private $create_by;
    private $modify_on;
    private $modify_at = '127.0.0.1';
    private $modify_by;

    //primary key
    private $primary_key = array("so_no");

    //auo increment
    private $increment_field = "";

    //instance method
    public function get_so_no()
    {
        return $this->so_no;
    }

    public function set_so_no($value)
    {
        $this->so_no = $value;
        return $this;
    }

    public function get_t3m_is_sent()
    {
        return $this->t3m_is_sent;
    }

    public function set_t3m_is_sent($value)
    {
        $this->t3m_is_sent = $value;
        return $this;
    }

    public function get_t3m_in_file()
    {
        return $this->t3m_in_file;
    }

    public function set_t3m_in_file($value)
    {
        $this->t3m_in_file = $value;
        return $this;
    }

    public function get_t3m_result()
    {
        return $this->t3m_result;
    }

    public function set_t3m_result($value)
    {
        $this->t3m_result = $value;
        return $this;
    }

    public function get_card_holder()
    {
        return $this->card_holder;
    }

    public function set_card_holder($value)
    {
        $this->card_holder = $value;
        return $this;
    }

    public function get_card_type()
    {
        return $this->card_type;
    }

    public function set_card_type($value)
    {
        $this->card_type = $value;
        return $this;
    }

    public function get_card_no()
    {
        return $this->card_no;
    }

    public function set_card_no($value)
    {
        $this->card_no = $value;
        return $this;
    }

    public function get_card_bin()
    {
        return $this->card_bin;
    }

    public function set_card_bin($value)
    {
        $this->card_bin = $value;
        return $this;
    }

    public function get_card_last4()
    {
        return $this->card_last4;
    }

    public function set_card_last4($value)
    {
        $this->card_last4 = $value;
        return $this;
    }

    public function get_card_exp_month()
    {
        return $this->card_exp_month;
    }

    public function set_card_exp_month($value)
    {
        $this->card_exp_month = $value;
        return $this;
    }

    public function get_card_exp_year()
    {
        return $this->card_exp_year;
    }

    public function set_card_exp_year($value)
    {
        $this->card_exp_year = $value;
        return $this;
    }

    public function get_card_start_month()
    {
        return $this->card_start_month;
    }

    public function set_card_start_month($value)
    {
        $this->card_start_month = $value;
        return $this;
    }

    public function get_card_start_year()
    {
        return $this->card_start_year;
    }

    public function set_card_start_year($value)
    {
        $this->card_start_year = $value;
        return $this;
    }

    public function get_card_issue_no()
    {
        return $this->card_issue_no;
    }

    public function set_card_issue_no($value)
    {
        $this->card_issue_no = $value;
        return $this;
    }

    public function get_fd_proc_status()
    {
        return $this->fd_proc_status;
    }

    public function set_fd_proc_status($value)
    {
        $this->fd_proc_status = $value;
        return $this;
    }

    public function get_fd_status()
    {
        return $this->fd_status;
    }

    public function set_fd_status($value)
    {
        $this->fd_status = $value;
        return $this;
    }

    public function get_cc_action()
    {
        return $this->cc_action;
    }

    public function set_cc_action($value)
    {
        $this->cc_action = $value;
        return $this;
    }

    public function get_create_on()
    {
        return $this->create_on;
    }

    public function set_create_on($value)
    {
        $this->create_on = $value;
        return $this;
    }

    public function get_create_at()
    {
        return $this->create_at;
    }

    public function set_create_at($value)
    {
        $this->create_at = $value;
        return $this;
    }

    public function get_create_by()
    {
        return $this->create_by;
    }

    public function set_create_by($value)
    {
        $this->create_by = $value;
        return $this;
    }

    public function get_modify_on()
    {
        return $this->modify_on;
    }

    public function set_modify_on($value)
    {
        $this->modify_on = $value;
        return $this;
    }

    public function get_modify_at()
    {
        return $this->modify_at;
    }

    public function set_modify_at($value)
    {
        $this->modify_at = $value;
        return $this;
    }

    public function get_modify_by()
    {
        return $this->modify_by;
    }

    public function set_modify_by($value)
    {
        $this->modify_by = $value;
        return $this;
    }

    public function _get_primary_key()
    {
        return $this->primary_key;
    }

    public function _get_increment_field()
    {
        return $this->increment_field;
    }

}
?>